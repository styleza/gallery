<?php

class model_top{
    private $photoTable;
    
    public function __construct() {
        $this->photoTable = new model_table_photo();
    }
    
    public function getNewestPictures($n = 3){
        return $this->photoTable->fetchConditional(array(), null, "id DESC", $n);
    }
    
    public function getTopRated($n = 3){
        return $this->photoTable->fetchConditional(array("rating_count > ?" => 0), array("*","rating_sum/rating_count as rating"), "rating DESC", $n);
    }
}