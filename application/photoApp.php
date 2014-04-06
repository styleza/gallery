<?php

class photoApp extends application {
    
    protected function pre(){
        
        
        $login = resources::get('session')->login;
        $this->createNavigation($login);
        $this->validateRoute($login);
        
    }
    protected function post(){
        header('Content-Type: text/html; charset=utf-8');
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
            $navigation->populate(new mvc_navigation_entry('list_own_images','list/own'));
        }
        
        resources::set('navigation',$navigation);
    }
    
    private function validateRoute($login){
        $unAuthAllowed = array('/','index/index','auth/login','user/register',
            'image/view','list/user','auth/authenticate','user/postregister',
            'test/db','test/connection','list/tag','image/get');
        
        $authAllowed = array('/','index/index','auth/logout','user/edit','list/user',
            'image/view','test/db','test/connection','image/add','list/tag','image/postimage','list/own',
            'image/get','image/comment','image/remove','image/rate');
        
        $controllerAction = $this->_request->controller . '/' . $this->_request->action;

        if(!$login){
            if(!in_array($controllerAction,$unAuthAllowed)){
                throw new Exception("403");
            }
        } else {
            if(!in_array($controllerAction,$authAllowed)){
                throw new Exception("403");
            }
        }
    }
}
