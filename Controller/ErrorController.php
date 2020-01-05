<?php


namespace base\controllers;


use base\Page;
use base\View\View;

class ErrorController
{
    private $page;

    private $error404;
    private $accessError;

    /**
     * ErrorController constructor.
     * @param $page Page
     * @param $errors
     */
    public function __construct(Page &$page, $errors)
    {
        $this->page = $page;

        $this->error404 = $errors['404'];
        $this->accessError = $errors['access'];
    }

    public function error404()
    {
        $view = new View($this->error404, $this->page);
    }

    public function accessError()
    {
        $view = new View($this->accessError, $this->page);
    }
}