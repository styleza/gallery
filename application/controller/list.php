<?php
class controller_list extends mvc_controller_abstract {
    private $photoModel;
    
    public function init(){
        $this->photoModel = new model_photo();
    }
    
    public function userAction(){
        $this->layout->title = 'list';
        $this->view->isownpage = false;
        $this->changeView('list');
    }
    
    public function tagAction(){
        $this->view->images = $this->photoModel->getPhotosByTagId($this->request->id);
        $this->view->isownpage = false;
        $this->changeView('list');
    }
    
    public function ownAction(){
        $this->view->images = $this->photoModel->getPhotosByUserId(resources::get('session')->user->id);
        $this->view->isownpage = true;
        $this->changeView('list');
    }
}