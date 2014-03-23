<?php
class mvc_navigation {
    private $_navi;
    
    public function __construct() {
        $this->_navi = array();
    }
    
    public function populate(mvc_navigation_entry $entry){
        $this->_navi[] = $entry;
    }
    
    public function getNavigation(){
        return $this->_navi;
    }
}