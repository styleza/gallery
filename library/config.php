<?php

class config {
    private $_configData;
    
    public function __construct(array $configArray) {
       $this->_configData = array();
       $this->_buildConfig($configArray);
    }
    
    private function _buildConfig($configArray){
        foreach($configArray as $key => $config){
            if(is_array($config)){
                $this->_configData[$key] = new self($config);
            } else {
                $this->_configData[$key] = $config;
            }
        }
    }
    
    public function get($key,$default = null){
        if(isset($this->_configData[$key])){
            return $this->_configData[$key];
        } else {
            return $default;
        }
    }
}