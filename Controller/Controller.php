<?php

namespace base\controllers;

use base\App;
use base\interfaces\ControllerInterface;
use base\Page;
use base\routing\Path;


abstract class Controller implements ControllerInterface
{
    public $page;
    public $params;

    public function beforeAction()
    {
        $this->checkAuth();
        $this->checkAccess();
        $this->checkAuthToken();
    }

    public function afterAction()
    {
        /** TODO: В разработке */
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

    protected function checkAuth()
    {
        if ($this->page->auth) {
            if (!isset($_COOKIE['auth_token'])) {
                if (empty($_COOKIE['auth_token']) || !App::$session->user->isAuth()) {
                    $path = new Path();
                    App::$session->prevPage = $path->getUrl();
                    header("Location: " . App::$config->authUrl);
                }
            }
        }
    }

    protected function checkAccess()
    {
        $this->page->access = true;
    }

    protected abstract function checkAuthToken();
}