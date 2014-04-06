<?php
class controller_index extends mvc_controller_abstract {
    public function indexAction(){
        $this->layout->title = 'index';
        $topModel = new model_top();
        $this->view->newest = $topModel->getNewestPictures();
        $this->view->best = $topModel->getTopRated();
        
    }
}