<?php
class mvc_request {
    private $_data;
    
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';
    
    const CONTROLLER_PREFIX = 'controller_';
    const ACTION_POSTFIX = 'Action';
    
    public function __construct(array $requestArray = array()) {
        $this->_data = $requestArray;
    }
    
    public function __get($name){
        if(isset($this->_data[$name])){
            return $this->_data[$name];
        }
        return null;
    }
    
    public function __set($name,$value){
        $this->_data[$name] = $value;
    }
    
    public function getController(){
        if($this->controller){
            return $this->controller;
        }
        return self::DEFAULT_CONTROLLER;
    }
    
    public function getAction(){
        if($this->action){
            return $this->action;
        }
        return self::DEFAULT_ACTION;
    }
    
    public function getControllerClass(){
        return self::CONTROLLER_PREFIX . $this->getController();
    }
    
    public function getActionMethod(){
        return $this->getAction() . self::ACTION_POSTFIX;
    }
}