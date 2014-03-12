<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of row
 *
 * @author Ilari
 */
abstract class db_table_row {
    protected $primaryKey;
    protected $dbTableClass;
    
    public function getParentTable(){
        $dbTableClassName = $this->dbTableClass;
        return $dbTableClassName::$dbTableName;
    }
    
    public function save(){
        $isUpdate = ($this->{$this->primaryKey} ? true : false);

        $sql = '';

        $dbTable = $this->getParentTable();
        
        $cols = get_class_vars(get_class($this));
        foreach($cols as $key => $dat){
            if(in_array($key,array('primaryKey','dbTableClass'))){
                unset($cols[$key]);
            }
        }
       
        $bindings = array();
        
        if(!$isUpdate){
            $sql .= 'INSERT INTO ' . $dbTable;
            
            $sql .= ' (' . implode(',',array_keys($cols)) . ') VALUES (';
            $sql .= implode(',',array_fill(0,count($cols),'?')) . ')';
            foreach($cols as $col => $value){
                $bindings[] = $this->{$col};
            }
            
        } else {
            $sql .= 'UPDATE ' . $dbTable . ' SET ';
            foreach($cols as $col => $data){
                $sql .= $col . '=' . (is_null($data) ? 'null' : '?').',';
                if(!is_null($data)){
                    $bindings[] = $data;
                }
            }
            $sql = substr($sql,0,-1);
            $sql .= ' WHERE ' . $this->primaryKey . ' = ?';
            $bindings[] = $this->{$this->primaryKey};
        }
        return resources::get('adapter')->runSql($sql,$bindings);
    }
    
    public function delete(){
        if(!$this->{$this->primaryKey}){
            throw new Exception('cant remove row that does not exists in database');
        }
        $sql = 'DELETE FROM ' . $this->getParentTable() . ' WHERE ' . $this->primaryKey . ' = ?';
        return resources::get('adapter')->runSql($sql,$this->{$this->primaryKey});
    }
}
