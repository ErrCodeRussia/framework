<?php

namespace base;

use base\controllers\ErrorController;
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

    private $ini;

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

        if (!defined("CONFIG"))
            define("CONFIG", $_SERVER['DOCUMENT_ROOT'] . "/../config/");

        if (!defined("VIEWS"))
            define("VIEWS", $_SERVER['DOCUMENT_ROOT'] . "/../views/");

        $this->ini = parse_ini_file(CONFIG . "config.ini", true);
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

                    $controller = $this->controller;
                    $action = $this->action;

                    if (method_exists($this->controller, 'beforeAction'))
                        $this->controller->beforeAction();
                    $controller->$action();
                    if (method_exists($this->controller, 'afterAction'))
                        $this->controller->afterAction();

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
                }

                if (isset($this->controller) && isset($this->action)) {
                    $action = $this->action;

                    if (method_exists($this->controller, 'beforeAction'))
                        $this->controller->beforeAction();
                    $this->controller->$action();
                    if (method_exists($this->controller, 'afterAction'))
                        $this->controller->afterAction();
                }
            }
        }

        if (empty($this->controller)) {
            $this->controller = new ErrorController($this->page, $this->ini['errors']);
            $this->controller->error404();

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