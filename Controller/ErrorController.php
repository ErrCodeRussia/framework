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
        if (array_key_exists('access', $errors))
            $this->accessError = $errors['access'];
    }

    public function error404()
    {
        if (!$this->page->api)
            $view = new View($this->error404, $this->page);
    }

    public function accessError()
    {
        if (!$this->page->api)
            $view = new View($this->accessError, $this->page);
    }
}