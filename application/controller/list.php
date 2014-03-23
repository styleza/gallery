<?php
class controller_list extends mvc_controller_abstract {
    public function userAction(){
        $this->layout->title = 'list';
    }
    public function tagAction(){
        $this->redirect('list/user');
    }
}