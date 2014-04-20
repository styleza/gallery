<?php

class auth {
    private $_session;
    public function __construct() {
        $this->_session = resources::get('session');
    }
    
    public function authenticate($identity, $password, $dbTable, $passwordColumn = 'password',$saltColumn = 'password_salt', $loginTimestamp = 'last_login'){
        $id2 = array();
        
        foreach($identity as $id => $value){
            $id2[$id." = ?"] = $value;
        }
        
        $row = $dbTable->fetchRow($id2);
        
        if(!$row) return false;
        
        $hashedPassword = self::hashPassword($password,$row->{$saltColumn});
        
        if($row && $row->{$passwordColumn} == $hashedPassword){
            $row->{$loginTimestamp} = date("Y-m-d H:i:s");
            $row->save();
            $this->saveSessionData($identity,$row);
            return true;
        } else {
            return false;
        }
    }
    private function saveSessionData($identity,$userRow){
        $this->_session->identity = $identity;
        $this->_session->user = $userRow;
        $this->_session->login = true;
    }
    
    public static function hashPassword($password,$userSalt = ''){
        for($i = 0; $i < 10; $i++){
            $password = md5($password . $userSalt . resources::get('config')->get('pw_salt',''));
        }
        return $password;
    }
    
    public static function generateSalt($saltLength = 16){
        return preg_replace("/[ ]/e",'chr(array_search(mt_rand(0, 61) ,array_flip(array_merge(range(48, 57), range(65, 90), range(97, 122)))))', str_repeat(" ", $saltLength));
    }
}
