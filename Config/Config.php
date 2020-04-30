<?php

namespace base\config;

use base\exceptions\config\ConfigException;
use base\exceptions\config\ConfigFileException;
use base\exceptions\config\ConfigKeyException;
use base\exceptions\config\ConfigValuesException;

class Config
{
    /**
     *  Файл конфигурации содержит в себе многомерный массив. Его можно найти в
     * папке config/, которая лежит в корне проекта. Файл называется config.php.
     *
     *  Для взаимодействия с этими данными пользуйтесь публичными свойствами,
     * описанными ниже.
     */

    private $config;
    private $filePath;

    /**
     *  Хранит название проекта.
     */
    public $name;

    /**
     *  Хранит URL, который нужно открывать при переходе на site.name/. По
     * умолчанию задан как '/'.
     *
     *  Используйте, если вам нужен редирект со страницы / на /homeUrl.
     */
    public $homeUrl;

    /**
     *  Хранит URL, на котором хранится форма авторизации. По умолчанию задан
     * как '/auth/'.
     *
     *  Используется в случаях, когда для доступа к странице нужна авторизация.
     * Это прописывается в config/routing.php. После успешной авторизации будет
     * произведено перенаправление на запрашиваемую ранее страницу.
     */
    public $authUrl = "/auth/";

    /**
     *  Хранит данные о БД в виде одномерного массива с ключами:
     *  [ 'host', 'user', 'password', 'database' ]
     *
     * @var array
     */
    public $database;

    /**
     * @var $styles
     * @var $scripts
     *
     *  Хранят относительные ссылки на подключаемые файлы.
     * STYLES - относительно папки public_html/css/
     * SCRIPTS - относительно папки public_html/js/
     */
    public $styles;
    public $scripts;

    /**
     * @var $favicon
     *
     *  Хранит относительный путь к файлу иконки сайта.
     * Путь относительно public_html/
     */
    public $favicon;

    /**
     *  Хранит массив со ссылками на пользовательское представление ошибок
     * (view-файлы).
     *
     * @var array
     */
    public $errors;

    /**
     *  Хранит конфигурацию для Digital Signature Algorithm, которая используется
     * при создании ключей шифрования.
     */
    public $DSA;

    public function __construct()
    {
        try {
            if (!defined("CONFIG")) {
                throw new ConfigException();
            }
        }
        catch (ConfigException $e) {
            echo $e->getMessage();
            die();
        }

        $this->filePath = file_exists(CONFIG . "config.php") ? CONFIG . "config.php" : null;

        try {
            if (!isset($this->filePath)) {
                throw new ConfigFileException();
            }
        }
        catch (ConfigFileException $e) {
            echo $e->getMessage();
            die();
        }

        $this->config = require $this->filePath;
        $this->setValues();
    }

    private function setValues()
    {
        try {
            foreach ($this->config as $key => $value) {
                if (!property_exists((string)__NAMESPACE__ . "\Config", $key)) {
                    throw new ConfigKeyException($key);
                }

                $this->$key = $value;

                if (!isset($this->$key)) {
                    throw new ConfigValuesException($key, $value);
                }
            }
        }
        catch (ConfigKeyException $e) {
            echo $e->getMessage();
            die();
        }
        catch (ConfigValuesException $e) {
            echo $e->getMessage();
            die();
        }
    }
}