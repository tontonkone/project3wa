<?php 
namespace Src\core;

class Url{

    public static function location(string $lien): void
    {
        header('Location:'. $lien);
        exit();
     
    }

}