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
    public function dbAction(){
        $dbModel = new model_printdb();
        
        $this->view->db = $dbModel->getDb();
        $this->layout->title = 'db_dump';
    }
    
    public function connectionAction(){
        $this->view->adapter = resources::get('adapter');
    }
}
