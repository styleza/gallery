<?php
class controller_list extends mvc_controller_abstract {
    private $photoModel;
    
    public function init(){
        $this->photoModel = new model_photo();
    }
    
    public function userAction(){
        $this->layout->title = 'list';
        $this->view->images = $this->photoModel->getPhotosByUsername($this->request->username);
        $this->view->isownpage = false;
        $this->changeView('list');
    }
    
    public function tagAction(){
        $this->layout->title = 'tag_photos';
        $this->view->images = $this->photoModel->getPhotosByTagId($this->request->id);
        $this->view->isownpage = false;
        $this->changeView('list');
    }
    
    public function ownAction(){
        $this->layout->title = 'own_photos';
        $this->view->images = $this->photoModel->getPhotosByUserId(resources::get('session')->user->id);
        $this->view->isownpage = true;
        $this->changeView('list');
    }
    
    public function searchAction(){
        if($this->request->searchDesc && strlen($this->request->searchDesc) >= 3){
            $this->view->images = $this->photoModel->searchByDescription($this->request->searchDesc);
            $this->view->isSearch = true;
        } else if($this->request->searchTag && strlen($this->request->searchTag) >= 3){
            $this->view->images = $this->photoModel->searchByTags($this->request->searchTag);
            $this->view->isSearch = true;
        }
    }
}