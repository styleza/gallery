<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of test
 *
 * @author Ilari
 */
class controller_test extends mvc_controller_abstract {
    public function indexAction(){
        $userTable = new model_table_users();
        $user = $userTable->createRow();
        $user->username = 'test';
        $user->password = 'test';
        $user->save();
    }
}
