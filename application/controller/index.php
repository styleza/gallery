<?php
class controller_index extends mvc_controller_abstract {
    public function indexAction(){
        $this->layout->title = 'index';
        $topModel = new model_top();
        $this->view->newest = $topModel->getNewestPictures();
        $this->view->best = $topModel->getTopRated();
        $this->view->tags = $topModel->getMostUsedTags();
        $this->view->message = $this->request->message;
    }
}