<?php

class model_entity_phototag extends db_table_row {
    //protected $primaryKey = 'id';
    protected $dbTableClass = 'model_table_phototag';
    
    public $photo_id;
    public $tag_id;
}
