<?php
/**
 * Description of abstract
 *
 * @author Ilari
 */
abstract class db_table_abstract {
    public static $dbTableName = 'default';
    protected $entityClass;
    
    public function createRow(array $existingData = null){
        $newEntity = new $this->entityClass();
        
        if($existingData){
            foreach($existingData as $key => $val){
                $newEntity->{$key} = $val;
            }
        }
        
        return $newEntity;
    }
    
    public function getTableName(){
        $className = get_called_class();
        
        return $className::$dbTableName;
    }
    public function fetchConditional(array $conditions = array(), array $cols = null,string $order = null, int $limit = null){
        $sql = 'SELECT ';
        
        if(!$cols){
            $sql .= '* ';
        } else {
            $sql .= '('.implode(',', $cols).') ';
        }

        $sql .= 'FROM ' . $this->getTableName();
        
        
        if($conditions){
            $sql .= ' WHERE ';
            
            $sql .= implode(' AND ',array_keys($conditions));
        }
        if($order){
            $sql .= ' ORDER BY '. $order;
        }
        
        if($limit){
            $sql .= ' LIMIT '.$limit;
        }
        return $this->fetchSql($sql, array_values($conditions), true);
    }
    
    public function fetchSql($sql,array $bindings, $integrable){
        $result = resources::get('adapter')->runSql($sql,$bindings);
        
        $rv = array();
        
        foreach($result as $row){
            if($integrable){
                $rv[] = $this->createRow($row);
            } else {
                $rv[] = $row;
            }
        }
        return $rv;
    }
    
    public function fetchAll(){
        return $this->fetchConditional();
    }
    
    public function fetchRow(array $conditions = null, array $cols = null){
        
        $arRes = $this->fetchConditional($conditions, $cols,null,1);
        
        if($arRes){
            return $arRes[0];
        }
        return null;
    }
    
    public function insert(array $data){
        $row = $this->createRow($data);
        
        $row->save();
    }
    
    public function delete(array $conditions){
        $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE ';
        $sql .= implode(",",array_keys($conditions));
        return resources::get('adapter')->runSql($sql,array_values($conditions));
    }
}
