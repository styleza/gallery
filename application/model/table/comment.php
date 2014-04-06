<?php
class model_table_comment extends db_table_abstract {
    public static $dbTableName = 'comment';
    protected $entityClass = 'model_entity_comment';

    public function getCommentsForPhoto($photoId){
        $sql = 'SELECT c.*,u.username FROM comment c JOIN users u ON u.id = c.user_id WHERE c.photo_id = ?';
        $comments = $this->fetchSql($sql, array($photoId), false);
        return $comments;
        
    }

}