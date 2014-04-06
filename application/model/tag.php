<?php
class model_tag {
    private $tagTable;
    private $photoTagTable;
    
    public function __construct(){
        $this->tagTable = new model_table_tag();
        $this->photoTagTable = new model_table_phototag();
    }
    
    public function solveTags($tagString){
        $tags = explode(' ',$tagString);
        
        $result = $this->tagTable->getIdsForTags($tags);

        
        $rv = array();
        foreach($tags as $tag){
            $rv[$tag] = null;
        }
        foreach($result as $res){
            $rv[$res->tag_name] = $res->id;
        }

        foreach($rv as $key => $tag){
            if($tag === null){
                $rv[$key] = $this->tagTable->createRow(array('tag_name' => htmlspecialchars($key)))->save();
            }
        }
        
        return $rv;
    }
    
    public function tagPhoto($photoId,$tagId){
        if(is_array($tagId)){
            foreach($tagId as $ti){
                $this->tagPhoto($photoId,$ti);
            }
        } else {
            $this->photoTagTable->addTag($photoId,$tagId);
        }
    }
}