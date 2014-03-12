<?php
function __autoload($className){
    $classPath = str_replace("_","/",$className) . ".php";

    foreach(explode(PATH_SEPARATOR, get_include_path()) as $incPath){
        if(file_exists($incPath . '/' .$classPath)){
            require_once $classPath;
            return true;
        }
    }
    return false;
    
}