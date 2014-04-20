<?php
class model_table_photo extends db_table_abstract {
    public static $dbTableName = 'photo';
    protected $entityClass = 'model_entity_photo';
    
    public function getPhotoObjects($conditions){
        list($visCond,$user) = $this->getVisibilityCondition();
        $sql = 'SELECT p.* FROM photo p JOIN users u ON u.id = p.user_id WHERE '.$visCond.' AND ';
        $sql .= implode(' AND ',array_keys($conditions));
        return $this->parseResult($this->fetchSql($sql, array_merge(array($user),array_values($conditions)), true));
    }
    
    public function getPhotoObjectsByTag($tagIds){
        if(!is_array($tagIds)){
            $tagIds = array($tagIds);
        }
        list($visCond,$user) = $this->getVisibilityCondition();
        $sql = 'SELECT p.* FROM photo p JOIN photo_tag pt ON pt.photo_id = p.id'
                . ' WHERE '.$visCond.' AND pt.tag_id IN ('.implode(',',array_fill(0,count($tagIds),'?')).')';
       
        return $this->parseResult($this->fetchSql($sql, array_merge(array($user),array_values($tagIds)), true));
    }
    
    private function parseResult($sqlResult){
        $photos = array();
        $commentTable = new model_table_comment();
        $tagTable = new model_table_tag();
        $userTable = new model_table_users();
        foreach($sqlResult as $row){

            $photos[] = array("photo" => $row,
                "comments" => $commentTable->getCommentsForPhoto($row->id),
                "tags" => $tagTable->getTagsForPhoto($row->id),
                "user" => $userTable->fetchRow(array("id = ?" => $row->user_id))
            );
        }
        return $photos;
    }
    
    public function getVisibilityCondition(){
        $user = resources::get('session')->user ? resources::get('session')->user->id : 0;
        $visibilityCondition = '(visibility IN (0'.($user ? ',1)':')').' OR user_id = ?)';
        
        return array(
            $visibilityCondition,$user
        );
    }

}