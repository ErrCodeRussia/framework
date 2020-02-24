<?php

namespace base\routing;

class Router
{
    /**
     * @var $method - [ALL, GET, POST, PUT]
     * @var $link - строка с url
     * @var $controller - класс обработчика
     * @var $action - метод класса обработчика
     *
     * @var $path array - массив строк между / в url
     * @var $countOfPath - количество элементов $path
     */
    private $method;
    private $link;
    private $controller;
    private $action;
    private $auth;

    public $path;
    public $countOfPath;

    /**
     * Router constructor.
     * @param $method
     * @param $link
     * @param $controller
     * @param $action
     * @param bool $auth
     */
    public function __construct($method, $link, $controller, $action, $auth)
    {
        $this->method = $method;
        $this->link = $link;
        $this->controller = $controller;
        $this->action = $action;
        $this->auth = $auth;

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

    /**
     * @return bool
     */
    public function getAuth(): bool
    {
        return $this->auth;
    }
}