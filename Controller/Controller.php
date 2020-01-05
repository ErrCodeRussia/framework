<?php

namespace base\controllers;

use base\App;
use base\interfaces\ControllerInterface;
use base\Page;
use base\routing\Path;


class Controller implements ControllerInterface
{
    public $page;
    public $params;

    public $access;

    public function beforeAction()
    {
        if ($this->page->auth) {
            if (!App::$session->user->isAuth()) {
                $path = new Path();
                App::$session->prevPage = $path->getUrl();
                header("Location: " . App::$config->authUrl);
            }
        }

        if (isset($this->access)) {
            foreach ($this->access as $group => $roles) {
                if ($group == App::$session->user->getGroup()) {
                    foreach ($roles as $role) {
                        if ($role == App::$session->user->getRole()) {
                            $this->page->access = true;
                        } else {
                            continue;
                        }
                    }
                } else {
                    continue;
                }
            }

            $this->page->access = false;
        }
    }

    public function afterAction()
    {

    }

    /**
     * Controller constructor.
     * @param Page $page - объект страницы
     * @param $params - массив параметров из url
     */
    public function __construct(Page &$page, $params)
    {
        $this->page = $page;
        $this->params = $params;
    }
}