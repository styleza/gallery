<?php
class controller_image extends mvc_controller_abstract {
    public function addAction(){
        $this->layout->title = 'add_image';
    }
    
    public function viewAction(){
        $this->layout->title = 'view_image';
        $photoModel = new model_photo();
        
        $image = $photoModel->getPhotoByShortUrlId($this->request->id);
        if(!$image){
            throw new Exception("404");
        }

        $this->view->image = $image;
    }
    
    public function postimageAction(){
        $photoModel = new model_photo();
        try{
            $this->view->image = $photoModel->addImage($_FILES['image'],$this->request->description,$this->request->tags);
        } catch(Exception $e){
            $this->view->error = $e;
        }
        $this->changeView('add');
    }
    
    public function getAction(){
        $photoModel = new model_photo();
        
        if(!$photoModel->isCurrentUserValidToView($this->request->file)){
            throw new Exception("403");
        }
        
        preg_match("/([0-9]+)x([0-9]+)/",$this->request->size,$matches);
        if(count($matches) != 3){
            throw new Excpetion("invalid_request");
        }
        
        $photoModel->outputImage($this->request->file,intval($matches[1]),intval($matches[2]));
    }
    
    public function commentAction(){
        $photoModel = new model_photo();
        $photoModel->commentPhoto(
            $this->request->file,
            resources::get('session')->user->id,
            $this->request->comment
        );
        $this->redirect($this->request->returnPath);
    }
    
    public function removeAction(){
        $photoModel = new model_photo();
        $photoModel->remove($this->request->file_id);
        $this->redirect('list/own');
    }
    
    public function rateAction(){
        $photoModel = new model_photo();
        $photoModel->rateImage($this->request->file,$this->request->rating);
        exit(0);
    }
}