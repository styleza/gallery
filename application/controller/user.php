<?php

class controller_user extends mvc_controller_abstract {
    
    public function registerAction(){
        $this->layout->title = 'register';
    }
    
    public function editAction(){
        $this->layout->title = 'edit_own_info';
    }
    
    public function postregisterAction(){
        $userModel = new model_user();
        
        $result = $userModel->createNewUser($this->request->username,
                $this->request->password,
                $this->request->password2,
                $this->request->email);
        
        if($result !== true){
            $this->view->error = $result;
            $this->view->request = $this->request;
            $this->changeView('register');
        }
    }
}
