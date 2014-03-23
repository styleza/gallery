<?php

class mvc_view{
    private $_data;
    private $_translator;
    
    public function __construct(array $data = array()) {
        $this->_data = $data;
        $this->_translator = resources::get('translator');
    }
    
    public function __set($name, $value){
        $this->_data[$name] = $value;
    }
    
    public function __get($name) {
        if(isset($this->_data[$name])){
            return $this->_data[$name];
        }
        return null;
    }
    
    public function render($viewScript){
        ob_start();
        include $viewScript;
        return ob_get_clean();
    }
    
    public function translate($key){
        return $this->_translator->_($key);
    }
    
    public function partial($viesScript, array $data = array()){
        $view = new self($data);
        return $view->render($viesScript);
    }
    
    public function baseUrl($file = ""){
        return resources::get('config')->get('baseUrl','') . '/' . $file;
    }
    public function resource($resourceName){
        return resources::get($resourceName);
    }
}