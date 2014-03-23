<?php
require_once 'autoloader.php';
require_once 'errorhandler.php';

class application {
    protected $_request;
    protected $_output;
    protected $_router;
    
    public function __construct(){

    }
    
    protected function pre(){
        
    }
    
    protected function post(){
        
    }
    
    public function handleRequest(){
        $this->_output = resources::startMvc($this->_request);
    }
    
    public static function getApplicationInstance($appName){
        if(class_exists($appName)){
            return new $appName();
        }
    }
    
    public function run(array $request){
        if($this->_router){
            $request = array_merge($request,$this->_router->route($_SERVER['REQUEST_URI']));
        }
        $this->_request = new mvc_request($request);
        $this->pre();
        $this->handleRequest();
        $this->post();
        
        return $this->_output;
    }
    
    public function registerRouter($router){
        $this->_router = $router;
    }
}
