<?php

class model_entity_comment extends db_table_row {
    //protected $primaryKey = 'id';
    protected $dbTableClass = 'model_table_comment';
    
    public $photo_id;
    public $comment;
    public $comment_added;
    public $user_id;
}
