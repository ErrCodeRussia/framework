<?php


namespace base\interfaces;


use base\Page;

interface ControllerInterface
{
    /**
     * Controller constructor.
     * @param Page $page - объект страницы
     * @param $params - массив параметров из url
     */
    public function __construct(Page &$page, $params);
}