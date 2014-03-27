<?php

class photoApp extends application {
    
    protected function pre(){
        header('Content-Type: text/html; charset=utf-8');
        
        $login = resources::get('session')->login;
        $this->createNavigation($login);
        $this->validateRoute($login);
        
    }
    
    private function createNavigation($login){
        $navigation = new mvc_navigation();
        
        $navigation->populate(new mvc_navigation_entry('index',''));
        if(!$login){
            $navigation->populate(new mvc_navigation_entry('login','auth/login'));
            $navigation->populate(new mvc_navigation_entry('register','user/register'));
        } else {
            $navigation->populate(new mvc_navigation_entry('add_image','image/add'));
            $navigation->populate(new mvc_navigation_entry('logout','auth/logout'));
            $navigation->populate(new mvc_navigation_entry('edit_own_info','user/edit'));
            $navigation->populate(new mvc_navigation_entry('list_images','list/user'));
        }
        
        resources::set('navigation',$navigation);
    }
    
    private function validateRoute($login){
        $unAuthAllowed = array('/','index/index','auth/login','user/register',
            'image/view','list/user','auth/authenticate','user/postregister',
            'test/db','test/connection');
        
        $authAllowed = array('/','index/index','auth/logout','user/edit','list/user',
            'image/view','test/db','test/connection');
        
        $controllerAction = $this->_request->controller . '/' . $this->_request->action;

        if(!$login){
            if(!in_array($controllerAction,$unAuthAllowed)){
                throw new Exception("404");
            }
        } else {
            if(!in_array($controllerAction,$authAllowed)){
                throw new Exception("404");
            }
        }
    }
}
