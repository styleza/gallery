<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of resources
 *
 * @author Ilari
 */
class resources {
    private static $_registry;
    
    public static function init($configArray){
        self::$_registry = array();
        self::$_registry['config'] = new config($configArray);
        
        $dbConfig = $configArray['db'];
        
        self::$_registry['adapter'] = new $dbConfig['adapter_class']($dbConfig);
        self::$_registry['layout'] = self::get('config')->get('layout',false);
        self::$_registry['translator'] = self::getTranslator();
        self::$_registry['session'] = new session();
    }
    
    public static function get($resourceId){
        if(isset(self::$_registry[$resourceId])){
            return self::$_registry[$resourceId];
        }
        return null;
    }
    
    public static function getMvc($request){
        $controllerClass = 'controller_'.$request['controller'];

        return new $controllerClass($request);
        
    }
    
    public static function getTranslator($locale = null){
        
        if(!$locale){
            $locale = self::get('config')->get('locale','fi_FI');
        }
        
        if(!isset(self::$_registry['tr_'.$locale])){
            $translations = include 'translation/' . $locale . '.php';
            self::$_registry['tr_'.$locale] = new translator($translations);
        }

        return self::$_registry['tr_'.$locale];
    }
    
    public static function startMvc($request){
        $class = self::getMvc($request);
        return $class->run($request['action']);
    }

}
