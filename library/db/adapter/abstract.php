<?php

abstract class db_adapter_abstract {
    protected $_adapter;
    
    protected $_user;
    protected $_password;
    protected $_DSN;
    
    
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
        return $sth->execute($bindings);
    }
}
