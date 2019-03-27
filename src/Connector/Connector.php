<?php

class Connector
{
    public static function requireFolder($folder)
    {
        $explode_dir = explode("/", $folder);

        if (end($explode_dir))
            $folder .= '/';

        $fileList = array();

        if ($catalog = opendir($folder)) {
            do {
                $file = readdir($catalog);
                if ($file != false) {
                    $fileList[] = $file;
                    $count = true;
                }
                else {
                    $count = false;
                }
            } while ($count);

            foreach ($fileList as $key => $file) {
                if ($file == '.' || $file == '..') {
                    unset($fileList[$key]);
                }
                else {
                    $fileList[$key] = $folder . $file;
                }
            }
        }

        foreach ($fileList as $file) {
            if (is_dir($file))
                self::requireFolder($file);
            else
                require_once "Connector.php";
        }
    }
}