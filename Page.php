<?php

namespace base;

use base\security\Security;
use base\session\Session;

class Page
{
    /**
     * @var $api - переменная для проверки, нужно ли отрисовывать страничку (если сайт - API, то не нужно)
     */
    public $api = false;

    /**
     * @var $projectName - название проекта из файла config/config.ini
     */
    public $projectName;

    /**
     *  Контент в <head></head>
     *
     * @var $meta - метатеги
     * @var $styles - основные стили сайта
     * @var $scripts - скрипты, которые нужно использовать на сайте
     * @var $adminStyles - стили панели администраторов
     * @var $title - заголовок страницы
     * @var $description - описание страницы для поисковых роботов
     * @var $keywords - ключевые слова страницы для поисковых роботов
     */
    public $meta;
    public $styles;
    public $scripts;
    public $adminStyles;
    public $title;
    public $description;
    public $keywords;

    /**
     *  Части, одинаковые на всех страницах
     *
     * @var $header - верхняя часть сайта (меню и т.п.)
     * @var $footer - нижняя часть сайта (футер)
     * @var $adminMenu - меню в панели администраторов
     */
    public $header;
    public $footer;
    public $adminMenu;

    /**
     *  Контент конкретной страницы
     *
     * @var $data - содержит переменные PHP, которые используются во вьюхах
     * @var $content - содержимое вьюхи для конкретной страницы
     */
    public $data;
    public $content;

    /**
     *  Данные из запросов
     *
     * @var $get - хранит параметры, переданные GET-запросом
     * @var $post - хранит параметры, переданные POST-запросом
     */
    private $get;
    private $post;
    private $files;

    /**
     * @var $session - экземпляр класса Session()
     */
    public $session;

    /**
     * @var $module - модуль, к которому относится страници сайта
     * Благодаря ему можно закрыть доступ к определённым разделам сайта, например, к админ-панели.
     * Задаётся при определении роутинга в config/routing.php
     */
    public $module;

    public function __construct()
    {
        if (!defined("LAYOUTS"))
            define("LAYOUTS", $_SERVER['DOCUMENT_ROOT'] . "/../views/layouts/");

        $this->meta = LAYOUTS . "head/meta.php";
        $this->styles = LAYOUTS . "head/styles.php";
        $this->scripts = LAYOUTS . "head/scripts.php";
        $this->header = LAYOUTS . "body/header.php";
        $this->footer = LAYOUTS . "body/footer.php";

        if (!empty($_GET))
            $this->get = Security::protectData($_GET);
        if (!empty($_POST))
            $this->post = Security::protectData($_POST);
        if (!empty($_FILES))
            $this->files = $_FILES;

        $this->session = new Session();
    }

    public function generate()
    {
        $generator = new Generate($this);
    }

    /**
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return string
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @return mixed
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * @return string
     */
    public function getAdminStyles()
    {
        return $this->adminStyles;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @return string
     */
    public function getAdminMenu()
    {
        return $this->adminMenu;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }
}