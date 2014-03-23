<?php

class model_entity_photo extends db_table_row {
    protected $primaryKey = 'id';
    protected $dbTableClass = 'model_table_photo';
    
    public $id;
    public $file_id;
    public $file_name;
    public $user_id;
    public $description;
    public $short_url_id;
    public $rating_sum;
    public $rating_count;
    public $visibility;
}
