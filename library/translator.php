<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of translator
 *
 * @author Ilari
 */
class translator {
    private $_translations;
    
    public function __construct($translations) {
        
        $this->_translations = $translations;
    }
    
    public function _($key){
        if(isset($this->_translations[$key])){
            return $this->_translations[$key];
        }
        return $key;
    }
    
    public function translate($key){
        
        return $this->_($key);
    }
}
