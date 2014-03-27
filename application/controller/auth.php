<?php
class controller_auth extends mvc_controller_abstract {
    
    public function loginAction(){
        $this->layout->title = 'login';
    }
    
    public function authenticateAction(){
        $userTable = new model_table_users();
        $auth = new auth();
        
        $identity = array(
            "username" => $this->request->username
        );
        
        $authResult = $auth->authenticate($identity, $this->request->password, $userTable);
        
        if($authResult){
            $this->redirect('index/index');
        } else {
            $this->view->error = true;
            $this->changeView('login');
        }
        
    }
    
    public function logoutAction(){
        resources::get('session')->destroy();
        $this->redirect('index/index');
    }
}
