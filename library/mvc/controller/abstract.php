<?php
abstract class mvc_controller_abstract{
    protected $view;
    
    protected $layoutView;
    protected $layoutScript;
    
    protected $request;
    
    private $_controllerName;
    
    public function __construct(mvc_request $request) {

        $this->request = $request;
        
        $this->view = new mvc_view();
        $this->layout = new mvc_view();
        $this->layoutScript = resources::get('layout');
        
        $this->_controllerName = $request->getController();

    }
    
    public function run($action){
        $this->{$action."Action"}();
        $viewScript = 'views/' . $this->_controllerName . '/' . $action . '.phtml';
        
        if($this->layoutScript){
            $this->layout->content = $this->view->render($viewScript);
            return $this->layout->render('layout/'.$this->layoutScript);
        } else {
            return $this->view->render($viewScript);
        }
    }
    
}