<?php
class model_table_phototag extends db_table_abstract {
    public static $dbTableName = 'photo_tag';
    protected $entityClass = 'model_entity_phototag';

    public function addTag($photoId,$tagId){

            $this->createRow(array('photo_id' => $photoId, 'tag_id' => $tagId))->save();

        return true;
    }
}