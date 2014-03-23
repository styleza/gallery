<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of errorController
 *
 * @author Ilari
 */
class controller_error extends mvc_controller_abstract {
    public function init(){
        // general init
    }
    
    public function errorAction(){
        
    }
    
    public function exceptionAction(){
        $this->view->exception = $this->request->exception;
    }
}
