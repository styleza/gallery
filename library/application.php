<?php
require_once 'autoloader.php';
require_once 'errorhandler.php';

class application {
    protected $_request;
    protected $_output;
    
    public function __construct(){

    }
    
    protected function pre(){
        if(!isset($this->_request['controller'])){
            $this->_request['controller'] = 'index';
        }
        if(!isset($this->_request['action'])){
            $this->_request['action'] = 'index';
        }
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
        $this->_request = $request;
        $this->pre();
        $this->handleRequest();
        $this->post();
        
        return $this->_output;
    }
}
