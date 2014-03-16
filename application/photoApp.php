<?php

class photoApp extends application {
    
    public function __construct() {
        parent::__construct();
        
    }
    
    protected function pre(){
        header('Content-Type: text/html; charset=utf-8');
    }
}
