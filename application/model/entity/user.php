<?php

class model_entity_user extends db_table_row {
    protected $primaryKey = 'id';
    protected $dbTableClass = 'model_table_users';
    
    public $id;
    public $username;
    public $password;
    public $email;
}
