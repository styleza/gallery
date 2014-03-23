<?php
class mvc_navigation_entry {
    public $naviName;
    public $naviUrl;
    public $properties;
    
    public function __construct($naviName, $naviUrl, $properties = array()) {
        $this->naviName = $naviName;
        $this->naviUrl = $naviUrl;
        $this->properties = $properties;
    }
}