<?php
class resources {
    private static $_registry;
    
    public static function init($configArray){
        self::$_registry = array();
        self::$_registry['config'] = new config($configArray);
        
        $dbConfig = $configArray['db'];
        
        self::set('adapter',new $dbConfig['adapter_class']($dbConfig));
        self::set('layout',self::get('config')->get('layout',false));
        self::set('translator',self::getTranslator());
        self::set('session',new session());
    }
    
    public static function get($resourceId){
        if(isset(self::$_registry[$resourceId])){
            return self::$_registry[$resourceId];
        }
        return null;
    }
    
    public static function set($resourceId, $resourceData){
        self::$_registry[$resourceId] = $resourceData;
    }
    
    public static function getMvc(mvc_request $request){
        $controllerClass = $request->getControllerClass();
        
        return new $controllerClass($request);
        
    }
    
    public static function getTranslator($locale = null){
        
        if(!$locale){
            $locale = self::get('config')->get('locale','fi_FI');
        }
        
        if(!isset(self::$_registry['tr_'.$locale])){
            $appTranslations = include 'translation/' . $locale . '.php';
            $staticTranslations = include 'static/translation/' . $locale . '.php';
            self::$_registry['tr_'.$locale] = new translator(array_merge($appTranslations,$staticTranslations));
        }

        return self::$_registry['tr_'.$locale];
    }
    
    public static function startMvc(mvc_request $request){
        
        if(!class_exists($request->getControllerClass())){
            
            return self::getMvcByHttpReturnCode(404);
        }
        
        $class = self::getMvc($request);

        if(!method_exists($class,
                $request->getActionMethod())){
            
            return self::getMvcByHttpReturnCode(404);
            
        }
        
        return $class->run($request->getAction());
    }
    
    public static function getMvcByHttpReturnCode($code){
        
        switch($code){
            case 404:
                if(self::get('config')->get('404_action',false)){
                    
                    return self::startMvc(array('controller' => 'controller_error',
                        'action' => self::get('config')->get('404_action')));
                    
                } else {
                    return self::get404();
                }
        }
    }
    
    public static function get404(){
        $view = new mvc_view();
        return $view->render('static/view/404.phtml');
    }

}
