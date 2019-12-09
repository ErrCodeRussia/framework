<?php

namespace base\controllers;

use base\interfaces\ControllerInterface;
use base\Page;


class Controller implements ControllerInterface
{
    public $page;
    public $params;

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