<?php 
namespace Src\core;


class Rendering {

    public static function renderContent(string $path, array $variables = [])
    {
        extract($variables);
        /*  Importe les variables dans un tableaux */
        ob_start();
        require(VIEW . $path . '.phtml');
        $pageContent = ob_get_clean();
        require(VIEW .'layout.phtml');
    }
}