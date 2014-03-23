<?php

class model_entity_tag extends db_table_row {
    protected $primaryKey = 'id';
    protected $dbTableClass = 'model_table_tag';
    
    public $id;
    public $tag_name;
}
