<?php

class photoApp extends application {
    
    protected function pre(){
        header('Content-Type: text/html; charset=utf-8');
        
        $navigation = new mvc_navigation();
        
        $navigation->populate(new mvc_navigation_entry('index',''));
        $navigation->populate(new mvc_navigation_entry('login','auth/login'));
        $navigation->populate(new mvc_navigation_entry('register','user/register'));
        $navigation->populate(new mvc_navigation_entry('add_image','image/add'));
        $navigation->populate(new mvc_navigation_entry('logout','auth/logout'));
        $navigation->populate(new mvc_navigation_entry('edit_own_info','user/edit'));
        $navigation->populate(new mvc_navigation_entry('list_images','list/user'));       
        
        resources::set('navigation',$navigation);
    }
}
