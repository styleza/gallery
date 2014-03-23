<?php

abstract class db_adapter_abstract {
    protected $_adapter;
    
    protected $_user;
    protected $_password;
    protected $_DSN;
    
    protected $_lastReturnValue;
    
    
    public function __construct($configArray) {
        $this->_user = $configArray['user'];
        $this->_password = $configArray['password'];
        $this->_DSN = $configArray['DSN'];
    }
    
    public function getAdapter(){
        return $this->_adapter;
    }
    
    public function runSql($sql,array $bindings = null){
        $sth = $this->_adapter->prepare($sql);
        
        $this->_lastReturnValue = $sth->execute($bindings);
        return $sth->fetchAll();
    }
    
    public function getLastReturnValue(){
        return $this->_lastReturnValue;
    }
}
