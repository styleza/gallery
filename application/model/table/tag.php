<?php
class model_table_tag extends db_table_abstract {
    public static $dbTableName = 'tag';
    protected $entityClass = 'model_entity_tag';

    public function getIdsForTags($tags){
        $selectSql = 'SELECT * FROM tag WHERE tag_name IN('.implode(',',array_fill(0,count($tags),'?')).')';
        return $this->fetchSql($selectSql, $tags, true);
    }
    
    public function getTagsForPhoto($photoId){
        $selectSql = 'SELECT t.* FROM tag t JOIN photo_tag pt ON pt.tag_id = t.id WHERE pt.photo_id = ?';
        $rv = array();
        $sqlRes = $this->fetchSql($selectSql,array($photoId),true);
        foreach($sqlRes as $sr){
            $rv[$sr->id] = $sr->tag_name;
        }
        return $rv;
    }
}