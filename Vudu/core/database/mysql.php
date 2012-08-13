<?php

class MySQLDatabase {
    private $link;
    
    private $fields="";
    private $table="";
    private $where="";
    private $join="";
    private $orderby="";
    private $limit="";


    public function __construct() {
        $this->connect();
    }

    private function connect() {
        global $config;
        $this->link = mysql_connect($config['db_host'], $config['db_user'], $config['db_pass']);
        if($this->link){
            mysql_select_db($config['db_name']);
        }
    }
    
    public function escape($str) {
        return mysql_real_escape_string($str);
    }
    
    public function query($query) {
        return mysql_query($query);
    }
    
    public function insert($table, $data, $escape=true) {
        $query = "INSERT INTO `".($escape ? $this->escape($table) : $table)."` SET ";
        $values = array();
        foreach($data as $k=>$v) {
            if($escape) $values[] = "`".$this->escape($k)."`=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
            else $values[] = $k."=".(is_numeric($v) ? $v : ("'".$v."'"));
        }
        $query .= implode(',', $values);
        return $this->query($query);
    }
    
    public function select($fields, $escape=true) {
        if(is_array($fields)){
            $f = array();
            foreach($fields as $k=>$v){
                if($escape) $f[] = "`".$this->escape($v)."`".(!is_numeric($k) ? ' as '.$this->escape($k) : '');
                else $f[] = $v.(!is_numeric($k) ? ' as '.$k : '');
            }
            $f = implode(',', $f);
        }else {
            $f = ($escape ? (strpos($fields, ',') === false ? $this->escape($fields) : $fields) : $fields);
        }
        $this->fields = ($this->fields != "" ? $this->fields . "," . $f : $f);
    }
    
    public function from($table, $escape=true){
        $this->table = ($escape ? (strpos($table, ',') === false ? $this->escape($table) : $table) : $table);
    }
    
    public function where($condition, $joiner='AND', $inner_joiner='AND', $braces=true, $escape=true){
        switch(strtoupper($joiner)){
            case 'AND':
                return $this->and_where($condition, $inner_joiner, $braces, $escape);
            case 'OR':
                return $this->or_where($condition, $inner_joiner, $braces, $escape);
        }
        
        return $this->and_where($condition, $inner_joiner, $braces, $escape);
    }
    
    public function and_where($condition, $joiner='AND', $braces=true, $escape=true){
        if(is_array($condition)){
            $c = array();
            foreach($condition as $k=>$v){
                if($escape) $c[] = "`".$this->escape($k)."`=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
                else $c[] = $k."=".(is_numeric($v) ? $v : ("'".$v."'"));
            }
        }else {
            
        }
    }
    
    public function or_where($condition, $joiner='AND', $braces=true, $escape=true){
        
    }

    private function disconnect(){
        if($this->link){
            mysql_close($this->link);
        }
    }
    
    public function __destruct() {
        $this->disconnect();
    }
    
}


?>