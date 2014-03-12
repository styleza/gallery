<?php

class auth {
    private $_session;
    public function __construct() {
        $this->_session = resources::get('session');
    }
    
    public function authenticate($identity, $password, $dbTable, $passwordColumn = 'password'){
        $row = $dbTable->fetchRow($identity);
        $hashedPassword = self::hashPassword($password);
        
        if($row && $row->{$passwordColumn} == $hashedPassword){
            $this->saveSessionData($identity);
        }
    }
    private function saveSessionData($identity){
        $this->_session->username = $identity;
    }
    
    public static function hashPassword($password,$userSalt = ''){
        for($i = 0; $i < 10; $i++){
            $password = md5($password . $userSalt . resources::get('config')->get('pw_salt',''));
        }
        return $password;
    }
}
