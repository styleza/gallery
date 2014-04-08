<?php

class controller_user extends mvc_controller_abstract {
    private $userModel;
    
    public function init(){
        $this->userModel = new model_user();
    }
    
    public function registerAction(){
        $this->layout->title = 'register';
    }
    
    public function editAction(){
        $this->layout->title = 'edit_own_info';
    }
    public function posteditAction(){
        $result = $this->userModel->editUser(resources::get('session')->user->id,
                $this->request->email,
                $this->request->password,
                $this->request->password2);
        if($result !== true){
            $this->view->error = $result;
            $this->changeView('edit');
        }
    }
    
    public function postregisterAction(){
        $result = $this->userModel->createNewUser($this->request->username,
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
