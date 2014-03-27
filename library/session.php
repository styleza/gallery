<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of session
 *
 * @author Ilari
 */
class session {
    private $_data;
    
    public function __construct(){
        session_start();
        foreach($_SESSION as $key => $value){
            $this->_data[$key] = $value;
        }
    }
    
    public function __set($name,$value){
        $_SESSION[$name] = $value;
    }
    
    public function __get($name){
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }
        return null;
    }
    
    public function destroy(){
        session_destroy();
    }
}
