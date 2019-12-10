<?php

namespace base;

use base\routing\Path;
use base\routing\Routing;

class App
{
    private $page;
    private $path;
    private $routing;
    private $params;

    private $controllerName;
    private $controller;
    private $action;

    /**
     * Routing constructor.
     * @param $page Page;
     * @param $routing Routing;
     */
    public function __construct(&$page, $routing)
    {
        $this->page = $page;
        $this->path = new Path();
        $this->routing = $routing;
        $this->params = array();
    }

    public function run()
    {
        if (!empty($this->routing->rules)) {
            foreach ($this->routing->rules as $rule) {
                // проверяем метод
                if (strtoupper($rule->getMethod()) != 'ALL' && strtoupper($rule->getMethod()) !== strtoupper($_SERVER['REQUEST_METHOD'])) {
                    continue;
                }

                // проверяем полное совпадение пути
                if ($this->path->getUrl() === $rule->getLink()) {
                    $this->controllerName = $rule->getController();
                    $this->controller = new $this->controllerName($this->page, $this->params);
                    $this->action = $rule->getAction();

                    $this->page->module = $rule->getModule();

                    $controller = $this->controller;
                    $action = $this->action;

                    if (method_exists($this->controller, 'beforeAction'))
                        $this->controller->beforeAction();
                    $controller->$action();

                    break;
                }

                // проверяем количество элементов массива пути
                if (count($this->path->getPath()) != $rule->countOfPath) {
                    continue;
                }

                $temp = 1;

                // сверяем элементы массива
                foreach ($this->path->getPath() as $key => $item) {

                    if (substr($rule->path[$key], 0, 1) === '{' && substr($rule->path[$key], -1) === '}') {
                        $rule->path[$key] = str_replace("{", "", $rule->path[$key]);
                        $rule->path[$key] = str_replace("}", "", $rule->path[$key]);
                        $this->params[$rule->path[$key]] = $this->path->getPath()[$key];
                    }
                    else if ($rule->path[$key] !== $this->path->getPath()[$key]) {
                        $temp = 0;
                        continue;
                    }
                }

                if ($temp) {
                    $this->controllerName = $rule->getController();
                    $this->controller = new $this->controllerName($this->page, $this->params);
                    $this->action = $rule->getAction();
                    $this->page->module = $rule->getModule();
                }

                if (isset($this->controller) && isset($this->action)) {
                    $action = $this->action;

                    if (method_exists($this->controller, 'beforeAction'))
                        $this->controller->beforeAction();
                    $this->controller->$action();
                }
            }
        }

        if (empty($this->controller)) {
            $this->controller = new ErrorController($this->page);
            $this->controller->pageNotFound();

            return;
        }
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }
}