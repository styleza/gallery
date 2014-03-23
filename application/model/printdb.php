<?php
class model_printdb {
    public function getDb(){
        $models = glob(dirname(__FILE__)."/table/*.php");
        
        $db = array();

        foreach($models as $modelFile){
            $modelFile = str_replace(".php","",basename($modelFile));
            $modelClassName = "model_table_" . $modelFile;
            $modelClass = new $modelClassName();
            
            $db[$modelFile] = $modelClass->fetchAll();

        }
        
        return $db;
    }
}