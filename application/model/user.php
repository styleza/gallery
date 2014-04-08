<?php
class model_user {
    const USERNAME_ALREADY_IN_USE = 'username_already_in_use';
    const USERNAME_CONTAINS_INVALID_CHAR = 'username_contains_invalid_char';
    const PASSWORD_TOO_WEAK = 'password_is_too_weak';
    const EMAIL_NOT_VALID = 'email_is_not_valid';
    const PASSWORDS_DOESNT_MATCH = 'passwords_doesnt_match';
    const USERNAME_TOO_SHORT = 'username_is_too_short';
    
    private $userTable;
    
    public function __construct(){
        $this->userTable = new model_table_users();
    }
    
    public function isValidUser($username, $password1, $password2, $email){
        $errors = array();
        
        if($username !== null && !$this->usernameContainsOnlyValidChars($username)){
            $errors[] = self::USERNAME_CONTAINS_INVALID_CHAR;
        }
        
        if($username !== null &&$this->isUsernameInUse($username)){
            $errors[] = self::USERNAME_ALREADY_IN_USE;
        }
        
        if($username !== null && strlen($username) < 3){
            $errors[] = self::USERNAME_TOO_SHORT;
        }
        
        if(!$this->isValidPass($password1)){
            $errors[] = self::PASSWORD_TOO_WEAK;
        }
        
        if(!$this->isValidEmail($email)){
            $errors[] = self::EMAIL_NOT_VALID;
        }
        
        if($password1 !== $password2){
            $errors[] = self::PASSWORDS_DOESNT_MATCH;
        }
        
        return empty($errors) ? true : $errors;
    }
    
    public function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) === $email;
    }
    
    public function isUsernameInUse($username){
        return $this->userTable->fetchRow(array('username = ?' => $username)) !== null;
    }
    
    public function usernameContainsOnlyValidChars($username){
        return !preg_match('/[^0-9A-Za-z_-]+/',$username);
    }
    
    public function isValidPass($password){
        if(strlen($password) < 8) return false;
        return true;
        //return preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password,$a);
    }
    
    public function createNewUser($username, $password1, $password2, $email){
        $isValid = $this->isValidUser($username, $password1, $password2, $email);
        
        if($isValid === true){
            $userRow = $this->userTable->createRow();
            
            $salt = auth::generateSalt();
            
            $userRow->username = $username;
            $userRow->password = auth::hashPassword($password1, $salt);
            $userRow->password_salt = $salt;
            $userRow->email = $email;
            
            $userRow->save();
            return true;
        } else {
            return $isValid;
        }
    }
    public function editUser($userId,$email,$password1,$password2){
        $isValid = $this->isValidUser(null,$password1,$password2,$email);
        
        if($isValid === true){
            $userRow = $this->userTable->fetchRow(array('id = ?' => $userId));
            $userRow->password = auth::hashPassword($password1,$userRow->password_salt);
            $userRow->email = $email;
            $userRow->save();
            return true;
        }
        return $isValid;
    }
}