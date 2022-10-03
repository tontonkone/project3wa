<?php
namespace Src\core;

class Debug{

    static public function run($variable)
    {
        echo "<pre>";
        print_r($variable);
        echo "</pre>";

        echo "<pre>";
        var_dump($variable);
        echo "</pre>";
        exit;
    }
}