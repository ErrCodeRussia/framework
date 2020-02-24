<?php


namespace base\View;


class Element
{
    private $data = array();
    private $render = false;

    public function __construct($template, $dataArray = [])
    {
        $file = VIEWS . 'elements/' . strtolower($template) . ".php";

        if (file_exists($file)) {
            $this->render = $file;
        }
        else {
            echo "Файл [" . $file . "] не найден!";
            return;
        }

        $this->data = $dataArray;

        extract($this->data);
        require $file;
    }
}