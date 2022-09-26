<?php

function autoload_class_multiple_directory($class_name) 
{
    # List all the class directories in the array.
    $array_paths = [
    'controler/',
    'routes',
    'model/', 
    'repository/',
    ];

    foreach($array_paths as $path)
    {
        $file = sprintf('%s/%s.php', $path, $class_name);
        if(is_file($file)) 
        {
            require $file;
        } 

    }
}

spl_autoload_register('autoload_class_multiple_directory');
