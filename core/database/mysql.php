<?php


class MySQLDatabase {
    private $db;
    
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
        $this->db = new mysqli($config['DATABASE']['db_host'], $config['DATABASE']['db_user'], $config['DATABASE']['db_pass'], $config['DATABASE']['db_name']);
        if($this->db->connect_errno){
            echo "Failed to connect to MySQL: " . $this->db->connect_error;
        }
    }

    public function insert_id(){
        return $this->db->insert_id;
    }
    
    public function escape($str) {
        return $this->db->real_escape_string($str);
    }
    
    public function reset($fields=[]){
        if(empty($fields)) {
            $this->fields = $this->join = $this->limit = $this->orderby = $this->table = $this->where = "";
        }else{
            foreach ($fields as $f){
                if(isset($this->{$f})){
                    $this->{$f} = "";
                }
            }
        }
    }
    
    public function query($query="", $reset=true) {
        if(empty($query)){
            $query = "SELECT ".$this->fields." FROM ".$this->table." ".$this->join." WHERE ".$this->where. " ".$this->orderby. " ".$this->limit;
            if($reset){
                $this->reset();
            }
        }
        return $this->db->query($query);
    }
    
    public function getColumn($query=""){
        $res = $this->query($query);
        if($res && $res->num_rows){
            $row = $res->fetch_array(MYSQLI_NUM);
            $res->free();
            return $row[0];
        }
        return null;
    }
    
    public function getRow($query=""){
        $res = $this->query($query);
        if($res && $res->num_rows){
            $row = $res->fetch_assoc();
            $res->free();
            return $row;
        }
        return null;
    }
    
    public function getAll($query="", $indexBy=false){
        $res = $this->query($query);
        if($res && $res->num_rows){
            $result = array();
            while(($row = $res->fetch_assoc())){
                if($indexBy){
                    $result[$row[$indexBy]] = $row;
                }else {
                    $result[] = $row;
                }
            }
            $res->free();
            return $result;
        }
        return null;
    }
    
    public function tableExists($table){
        return $this->getRow("SHOW TABLES LIKE '$table'") != null;
    }
    
    public function createTable($table, $fields){
        $query = "CREATE TABLE IF NOT EXISTS `$table` (";
        $cols = array();
        foreach ($fields as $k=>$v){
            $cols[] = $this->backticks($k)." $v";
        }
        $query .= implode(",", $cols);
        $query .= ");";
        return $this->query($query);
    }
    
    private function backticks($field){
        if(preg_match("@^[a-zA-Z0-9_.\s]+$@", $field)) {
            if(strpos($field, ".")!==FALSE){
                $parts = explode(".", $field);
                foreach ($parts as $k=>$v){
                    $parts[$k] = "`".trim($v)."`";
                }
                $field = implode(".", $parts);
            }else{
                $field = "`".$field."`";
            }
        }
        return $field;
    }

    public function insert($table, $data, $escape=true, $on_duplicate="") {
        $query = "INSERT INTO `".($escape ? $this->escape($table) : $table)."` SET ";
        $values = array();
        foreach($data as $k=>$v) {
            if($escape) $values[] = $this->backticks($this->escape($k))."=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
            else $values[] = $this->backticks($k)."="."'".$v."'"; //(is_numeric($v) ? $v : ("'".$v."'"));
        }
        $query .= implode(',', $values);
        
        if(!empty($on_duplicate)){
            if(is_array($on_duplicate)) {
                $dvalues = array();
                foreach($on_duplicate as $k=>$v) {
                    if($escape) $dvalues[] = $this->backticks($this->escape($k))."=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
                    else $dvalues[] = $this->backticks($k)."="."'".$v."'"; //(is_numeric($v) ? $v : ("'".$v."'"));
                }
                $query .= " ON DUPLICATE KEY UPDATE ".implode(',', $dvalues);
            }else{
                $query .= " ON DUPLICATE KEY UPDATE ".$on_duplicate;
            }
        }
        return $this->query($query);
    }
    
    public function update($table, $data, $cond, $joinby='AND', $escape=true) {
        $query = "UPDATE `".($escape ? $this->escape($table) : $table)."` SET ";
        
        $values = array();
        foreach($data as $k=>$v) {
            if($escape) $values[] = $this->backticks($this->escape($k))."=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
            else $values[] = $this->backticks($k)."="."'".$v."'"; //(is_numeric($v) ? $v : ("'".$v."'"));
        }
        $query .= implode(',', $values);
        
        $conds = array();
        foreach($cond as $k=>$v) {
            if($escape) $conds[] = $this->backticks($this->escape($k))."=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
            else $conds[] = $k."="."'".$v."'"; //(is_numeric($v) ? $v : ("'".$v."'"));
        }
        $query .= implode(" $joinby ", $conds);
        return $this->query($query);
    }
    
    public function select($fields, $escape=true) {
        if(is_array($fields)){
            $f = array();
            foreach($fields as $k=>$v){
                if($escape) $f[] = $this->backticks($this->escape($v)).(!is_numeric($k) ? ' as '.$this->escape($k) : '');
                else $f[] = $this->backticks($v).(!is_numeric($k) ? ' as '.$k : '');
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
                if($escape) $c[] = $this->backticks($this->escape($k))."=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
                else $c[] = $this->backticks($k)."=".(is_numeric($v) ? $v : ("'".$v."'"));
            }
            $c = implode(" $joiner ", $c);
        }else {
            $c = $condition;
        }
        
        $this->where = "(". (empty($this->where) ? "$c" : ($this->where . " AND ($c)")) . ")";
        
    }
    
    public function or_where($condition, $joiner='AND', $braces=true, $escape=true){
        if(is_array($condition)){
            $c = array();
            foreach($condition as $k=>$v){
                if($escape) $c[] = $this->backticks($this->escape($k))."=".(is_numeric($v) ? $v : ("'".$this->escape($v)."'"));
                else $c[] = $this->backticks($k)."=".(is_numeric($v) ? $v : ("'".$v."'"));
            }
            $c = implode(" $joiner ", $c);
        }else {
            $c = $condition;
        }
        
        $this->where = "(". (empty($this->where) ? "$c" : ($this->where . " OR ($c)")) . ")";
    }

    private function disconnect(){
        if($this->db){
            $this->db->close();
        }
    }
    
    public function __destruct() {
        $this->disconnect();
    }
    
}
