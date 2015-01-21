<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LinkDB        
 *
 * @author laurent
 */
abstract class LinkDB {
    
    protected static function execute($sql,$params = null){
        $pdo = DatabaseAcces::getInstance();
        $stm = $pdo->prepare($sql);
        $stm->execute($params);
        return $stm;
    }
    
    protected static function executeAndFetchAll($sql,$params = null){
        if($stm = LinkDB::execute($sql, $params)){
            return $stm->fetchAll();
        }else{
            return Array();
        }
        
    }
    
    protected function bind($tab){
        if(count($tab)>0)
            {
            if(is_array($tab[0])){
                $tab = $tab[0];
            }
            foreach(get_class_vars(get_class($this)) as $key => $value){
                $this->$key = $tab[$key];
            }
        }
    }
    
    protected function save(){
        $class = get_called_class();
        if (isset($this->id)) {
            $sql = $this->update();
        } else {
            do {
                $id = uniqid(substr(strtolower($class), 0,3), true);
            } while ($class::exist($id));
            $this->id = $id;
            $sql = $this->insert();
        }
        
        $params = Array();
        foreach(get_class_vars($class) as $key => $value){
            $params[":$key"] = $this->$key;
        }
        $class::execute($sql,$params);
    }
    
    protected function update(){
        $table = strtolower(get_class($this));
        $sql = "UPDATE $table SET";
        $i=0;
        foreach(get_class_vars(get_class($this)) as $key => $value){
            if($i>0){
                $sql.=",";
            }
            if($key!="id")
            {
                $sql.=" $key = :$key";
                $i++;
            }
        }
        return $sql." WHERE id = :id";
    }
    
    protected function insert(){
        $table = strtolower(get_class($this));
        $sql ="INSERT INTO $table VALUES (";
        $i=0;
        foreach(get_class_vars(get_class($this)) as $key => $value){
            if($i>0){
                $sql.=",";
            }
            $sql.=" :$key";
            $i++;
        }
        $sql.=")";
        return $sql;
        
    }
    
    static function find($id){
        $classe = get_called_class();
        return $classe::generalFind($id,"id");
    }
    
    static protected function generalFind($val,$champ,$bind=TRUE,$activate=TRUE){
        $classe = get_called_class();
        $sql = "SELECT * FROM ".  strtolower($classe)." WHERE $champ=:$champ";
        
        $tab = get_class_vars($classe);
        
        if(array_key_exists('active',$tab) && $activate ){
            $sql.= " AND active = 1";
        }
        
        if($bind){
            $obj = new $classe();
            $obj->bind(LinkDB::executeAndFetchAll($sql,array(":$champ"=>$val)));
            return $obj;
        }else{
            return LinkDB::executeAndFetchAll($sql,array(":$champ"=>$val));
        }
        
    }
    
    static function exist($id){
        $classe = get_called_class();
        $retour = $classe::generalFind($id,'id',false,false);
        return !empty($retour);
    }
    
    abstract function getId();
}
