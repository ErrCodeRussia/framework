<?php

namespace base;

use base\config\Config;
use base\controllers\ErrorController;
use base\routing\Path;
use base\routing\Router;
use base\routing\Routing;
use base\session\Session;

class App
{
    /**
     *  В этой статичной переменной хранится обработчик файла конфигурации.
     * С помощью него можно получить нужные данные проекта из любой части
     * приложения, воспользовавшись конструкцией App::$config.
     *
     *  Класс Config работает с файлом config.php, который должен находиться
     * в папке config/ в корне вашего проекта.
     */
    public static $config;

    /**
     *  С помощью этой переменной можно обращаться к сессии и работать с ней.
     */
    public static $session;

    /**
     * @var $page Page;
     */
    private $page;
    private $path;
    private $routing;
    private $params;

    private $suitableRule;

    private $controllerName;
    private $controller;
    private $action;

    /**
     * Routing constructor.
     * @param $routing Routing;
     */
    public function __construct($routing)
    {
        self::$config = new Config();
        self::$session = new Session();

        $this->path = new Path();
        $this->routing = $routing;
        $this->params = array();

        if (!defined("CONFIG"))
            define("CONFIG", $_SERVER['DOCUMENT_ROOT'] . "/../config/");

        if (!defined("VIEWS"))
            define("VIEWS", $_SERVER['DOCUMENT_ROOT'] . "/../views/");
    }

    /**
     * @param $page Page;
     */
    public function setPage(&$page)
    {
        $this->page = $page;
    }

    public function run()
    {
        if ($this->path->getUrl() == '/' && self::$config->homeUrl != '/') {
            header("Location: " . self::$config->homeUrl);
            return;
        }

        if (!empty($this->routing->rules)) {
            foreach ($this->routing->rules as $rule) {
                // проверяем метод
                if (strtoupper($rule->getMethod()) != 'ALL' && strtoupper($rule->getMethod()) !== strtoupper($_SERVER['REQUEST_METHOD'])) {
                    continue;
                }

                // проверяем полное совпадение пути
                if ($this->path->getUrl() === $rule->getLink()) {
                    $this->params = [];
                    $this->page->auth = $rule->getAuth();

                    $this->controllerName = $rule->getController();
                    $this->controller = new $this->controllerName($this->page, $this->params);
                    $this->action = $rule->getAction();

                    $this->ControllerAction($this->controller, $this->action);

                    return;
                }

                // проверяем количество элементов массива пути
                if (count($this->path->getPath()) != $rule->countOfPath) {
                    continue;
                }

                $temp = 1;
                $updateParams = false;

                // сверяем элементы массива
                foreach ($this->path->getPath() as $key => $item) {

                    if (substr($rule->path[$key], 0, 1) === '{' && substr($rule->path[$key], -1) === '}') {
                        $rule->path[$key] = str_replace("{", "", $rule->path[$key]);
                        $rule->path[$key] = str_replace("}", "", $rule->path[$key]);
                        $this->params[$rule->path[$key]] = $this->path->getPath()[$key];
                        $updateParams = true;
                    }
                    else if ($rule->path[$key] !== $this->path->getPath()[$key]) {
                        $temp = 0;
                        continue;
                    }
                }

                if ($temp) {
                    $this->suitableRule['rule'] = $rule;
                    if ($updateParams) {
                        $this->suitableRule['params'] = $this->params;
                    }
                }
                else {
                    $this->params = [];
                }
            }

            if (isset($this->suitableRule)) {
                /** @var Router $rule */
                $rule = $this->suitableRule['rule'];
                $this->page->auth = $rule->getAuth();

                $this->controllerName = $rule->getController();
                $this->controller = new $this->controllerName($this->page, $this->params);
                $this->action = $rule->getAction();
            }

            if (isset($this->controller) && isset($this->action)) {
                $this->ControllerAction($this->controller, $this->action);
            }
        }

        if (empty($this->controller)) {
            $this->controller = new ErrorController($this->page, self::$config->errors);
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

    private function ControllerAction($controller, $action)
    {
        if (method_exists($controller, 'beforeAction'))
            $controller->beforeAction();

        if ($this->page->access === true || $this->page->access === null) {
            $controller->$action();
            if (method_exists($controller, 'afterAction'))
                $controller->afterAction();
        }
        else {
            $controller = new ErrorController($this->page, self::$config->errors);
            $controller->accessError();

            return;
        }
    }
}