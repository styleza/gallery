<?php
class model_table_photo extends db_table_abstract {
    public static $dbTableName = 'photo';
    protected $entityClass = 'model_entity_photo';
    
    public function getPhotoObjects($conditions){
        $allowed = array(0);
        if(resources::get('session')->login){
            $allowed[] = 1;
        }
        $myId = resources::get('session')->user ? resources::get('session')->user->id : 0;
        $sql = 'SELECT p.* FROM photo p JOIN users u ON u.id = p.user_id WHERE (p.visibility IN ('.implode(',',$allowed).') OR user_id = '.$myId.') AND ';
        $sql .= implode(' AND ',array_keys($conditions));
        return $this->parseResult($this->fetchSql($sql, array_values($conditions), true));
    }
    
    public function getPhotoObjectsByTag($tagId){
        $sql = 'SELECT p.* FROM photo p JOIN photo_tag pt ON pt.photo_id = p.id'
                . ' WHERE pt.tag_id = ?';
        return $this->parseResult($this->fetchSql($sql, array($tagId), true));
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