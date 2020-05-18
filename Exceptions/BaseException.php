<?php


namespace base\exceptions;


use base\App;
use base\log\Log;

class BaseException extends \Exception
{
    /**
     * @param string $message Пользовательское сообщение об ошибке
     * @param string $logPath Путь до файла, куда нужно записать логи
     */
    public function message($message = '', $logPath = '')
    {
        if (App::$config->exceptions === true) {
            if ($message !== '') {
                echo $message;
            }
            else {
                echo $this->getMessage();
            }
        }
        else {
            $log = new Log();
            $log->code = $this->getCode();
            $log->message = $this->getMessage();

            $logPath = ($logPath === '') ? App::$config->logs['log'] : $logPath;

            $log->save($logPath);
        }
    }
}