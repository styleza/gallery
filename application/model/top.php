<?php

class model_top{
    private $photoTable;
    
    public function __construct() {
        $this->photoTable = new model_table_photo();
    }
    
    public function getNewestPictures($n = 3){
        
        list($visibilityCondition,$user) = $this->photoTable->getVisibilityCondition();
        
        return $this->photoTable->fetchConditional(
                array($visibilityCondition => $user),
                null,
                "id DESC",
            $n);
    }
    
    public function getTopRated($n = 3){
        
        list($visibilityCondition,$user) = $this->photoTable->getVisibilityCondition();
        
        return $this->photoTable->fetchConditional(
                array(
                    "rating_count > ?" => 0,
                    $visibilityCondition => $user
                ),
                array("*","rating_sum/rating_count as rating"),
                "rating DESC",
                $n
            );
    }
    public function getMostUsedTags($n = 3){
        $tagTable = new model_table_tag();
        return $tagTable->getMostUsedTags($n);
    }
    
    
}