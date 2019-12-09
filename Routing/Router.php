<?php

namespace base\routing;

class Router
{
    /**
     * @var $link - строка с url
     * @var $module - название модуля сайта
     * @var $controller - класс обработчика
     * @var $action - метод класса обработчика
     * @var $method - [ALL, GET, POST, PUT]
     *
     * @var $path array - массив строк между / в url
     * @var $countOfPath - количество элементов $path
     */
    private $link;
    private $module;
    private $controller;
    private $action;
    private $method;

    public $path;
    public $countOfPath;

    /**
     * Router constructor.
     * @param $link
     * @param $module
     * @param $controller
     * @param $action
     * @param $method
     */
    public function __construct($method, $link, $module, $controller, $action)
    {
        $this->method = $method;
        $this->link = $link;
        $this->module = $module;
        $this->controller = $controller;
        $this->action = $action;

        $this->path = explode("/", $link);
        $this->countOfPath = count($this->path);
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    public function checkLink($link)
    {
        //
    }
}