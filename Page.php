<?php

namespace base;

use base\security\Security;

class Page
{
    /**
     * @var bool переменная для проверки, нужно ли генерировать страницу
     */
    public $generate = true;

    /**
     * @var $auth - переменная для проверки требуемости авторизации
     */
    public $auth;

    /**
     * @var $access - переменная для проверки доступа к странице
     */
    public $access;

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
     * @var $content - содержимое вьюхи для конкретной страницы
     */
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

    public function __construct()
    {
        if (!defined("LAYOUTS"))
            define("LAYOUTS", $_SERVER['DOCUMENT_ROOT'] . "/../views/layouts/");

        $this->meta = LAYOUTS . "head/meta.php";
        $this->header = LAYOUTS . "body/header.php";
        $this->footer = LAYOUTS . "body/footer.php";

        $this->setStyles();
        $this->setScripts();

        if (!empty($_GET))
            $this->get = Security::protectData($_GET);
        if (!empty($_POST))
            $this->post = Security::protectData($_POST);
        if (!empty($_FILES))
            $this->files = $_FILES;
    }

    public function generate()
    {
        $generator = new Generate($this);
    }

    private function setStyles()
    {
        $styles = App::$config->styles;
        $favicon = App::$config->favicon;

        if (empty($styles))
            return;

        $string = '';
        if ($favicon != '')
            $string .= "<link rel='icon' href='/{$favicon}' type='image/x-icon'>";

        foreach ($styles as $style) {
            $string .= "<link rel='stylesheet' href='/css/{$style}'>";
        }

        $this->styles = $string;
    }

    private function setScripts()
    {
        $scripts = App::$config->scripts;

        if (empty($scripts))
            return;

        $string = '';
        foreach ($scripts as $script) {
            $string .= "<script src='/js/{$script}'></script>";
        }

        $this->scripts = $string;
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

    public static function checkContent($file)
    {
        return file_get_contents(VIEWS . "layouts/body/{$file}.php");
    }
}