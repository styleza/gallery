<?php
class controller_auth extends mvc_controller_abstract {
    
    public function loginAction(){
        $this->layout->title = 'login';
    }
    
    public function authenticateAction(){
        $this->redirect('index/index');
    }
    
    public function logoutAction(){
        $this->redirect('index/index');
    }
}
