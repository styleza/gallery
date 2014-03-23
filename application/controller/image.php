<?php
class controller_image extends mvc_controller_abstract {
    public function addAction(){
        $this->layout->title = 'add_image';
    }
    public function viewAction(){
        $this->layout->title = 'view_image';
    }
}