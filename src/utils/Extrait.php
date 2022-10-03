<?php 
namespace Src\utils;

class Extrait{
    
    public static function text(string $content , int $limit = 50)
    {
        if (mb_strlen($content) <= $limit)
        {
            return $content;
        }
        $space = mb_strpos($content , ' ', $limit);
        return substr($content, 0,$space) . '...';
    }
}