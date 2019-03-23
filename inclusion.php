<?php

function inclusion($dir) {

    $explode_dir = explode("/", $dir);

    if (end($explode_dir))
        $dir .= '/';

    $fileList = array();

    if ($catalog = opendir($dir)) {
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
                $fileList[$key] = $dir . $file;
            }
        }
    }

    foreach ($fileList as $file) {
        if (is_dir($file))
            inclusion($file);
        else
            require_once "inclusion.php";
    }
}