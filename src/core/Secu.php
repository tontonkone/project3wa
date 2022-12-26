<?php
namespace Src\core;

class Secu{
    
    static public function  inputValid($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_NOQUOTES, "UTF-8");
        return $data;
    }
}