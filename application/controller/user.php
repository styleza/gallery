<?php

class controller_user extends mvc_controller_abstract {
    public function registerAction(){
        $this->layout->title = 'register';
    }
    public function editAction(){
        $this->layout->title = 'edit_own_info';
    }
}
