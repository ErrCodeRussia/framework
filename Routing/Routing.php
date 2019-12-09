<?php

namespace base\routing;

class Routing
{
    /**
     * @var $rules Router[]
     */
    public $rules;

    public function __construct()
    {
        $this->rules = array();
    }

    public  function add($method, $link, $module, $controller, $action)
    {
        $this->rules[] = new Router($method, $link, $module, $controller, $action);
    }
}