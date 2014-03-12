<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mysql
 *
 * @author Ilari
 */
class db_adapter_mysql extends db_adapter_abstract {
    
    public function __construct($configArray) {
        parent::__construct($configArray);

        $this->connect();
    }
    
    public function connect(){
        $this->_adapter = new PDO($this->_DSN,$this->_user,$this->_password);
        $this->_adapter->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }
    
    
}
