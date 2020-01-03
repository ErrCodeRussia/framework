<?php

namespace base\View;

use base\Page;


class View
{
    private $data = array();
    private $render = false;
    private $page;

    /**
     * View constructor.
     * @param $template
     * @param $page Page
     * @param array $dataArray
     */
    public function __construct($template, &$page, $dataArray = [])
    {
        $this->page = $page;
        $file = VIEWS . strtolower($template) . ".php";

        if (file_exists($file)) {
            $this->render = $file;
        }
        else {
            echo "Файл [" . $file . "] не найден!";
            return;
        }

        $this->data = $dataArray;
        $this->data['page'] = $page;

        ob_start();
        extract($this->data);
        require $file;

        $page->setContent(ob_get_clean());
    }
}