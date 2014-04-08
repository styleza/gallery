<?php
class controller_image extends mvc_controller_abstract {
    private $photoModel;
    public function init(){
        $this->photoModel = new model_photo();
    }
    public function addAction(){
        $this->layout->title = 'add_image';
    }
    
    public function viewAction(){
        $this->layout->title = 'view_image';
        
        $image = $this->photoModel->getPhotoByShortUrlId($this->request->id);
        if(!$image){
            $this->redirect('index/index/message/1');
        }

        $this->view->image = $image;
        $this->view->isLoggedIn = resources::get('session')->login;
    }
    
    public function postimageAction(){
        try{
            $this->view->image = $this->photoModel->addImage(
                    $_FILES['image'],
                    htmlspecialchars($this->request->description),
                    $this->request->tags);
        } catch(Exception $e){
            $this->view->error = $e;
        }
        $this->changeView('add');
    }
    
    public function getAction(){
        if(!$this->photoModel->isCurrentUserValidToView($this->request->file)){
            throw new Exception("403");
        }
        
        preg_match("/([0-9]+)x([0-9]+)/",$this->request->size,$matches);
        if(count($matches) != 3){
            throw new Excpetion("invalid_request");
        }
        
        $this->photoModel->outputImage($this->request->file,intval($matches[1]),intval($matches[2]));
    }
    
    public function commentAction(){
        $this->photoModel->commentPhoto(
            $this->request->file,
            resources::get('session')->user->id,
            htmlspecialchars($this->request->comment)
        );
        $this->redirect($this->request->returnPath);
    }
    
    public function removeAction(){
        $this->photoModel->remove($this->request->file_id);
        $this->redirect('list/own');
    }
    
    public function rateAction(){
        $this->photoModel->rateImage($this->request->file,$this->request->rating);
        exit(0);
    }
    
    public function changeprivacyAction(){
        $photoModel = new model_photo();
        
        $visibility = intval($this->request->privacy);
        
        if($visibility < 0 || $visibility > 2){
            throw new Exception("illegal_input");
        }
        
        $photoModel->changeVisibility($this->request->file,$visibility);
        $this->redirect('list/own');
    }
}