<?php

/*
 * Name        : Request.php
 * Author      : Sandeep Singh
 * Email       : s4nd33p@gmail.com
 * Description : This defines the basic and bare minimum functionality of 
 * handling WS client's requests. It supports various methods of data 
 * communication from client to WS server (like GET, POST, JSON, etc)
 */

class Request implements IRequest{
    private $method;
    private $data;
    public function __construct($method='auto'){
        $this->method = strtolower($method);
        $this->parseRequest();
        $this->clearAllGlobalArrays();
    }
    private function clearAllGlobalArrays(){
        foreach($_GET as $k=>$v){
            unset($_GET[$k]);
        }
        foreach($_POST as $k=>$v){
            unset($_POST[$k]);
        }
        foreach($_REQUEST as $k=>$v){
            unset($_REQUEST[$k]);
        }
    }
    private function parseRequest(){
        $this->data = array();
        switch($this->method){
            case 'get':
                $this->parseGet();
                break;
            case 'post':
                $this->parsePost();
                break;
            case 'json':
                $this->parseJSON();
                break;
            case 'get_post':
                $this->parseGet();
                $this->parsePost();
                break;
            case 'get_json':
                $this->parseGet();
                $this->parseJSON();
                break;
            case 'auto':
                if($this->detectMethod()){
                    $this->parseRequest();
                }
                break;
            default:
                if($this->detectMethod()){
                    $this->parseRequest();
                }
        }
    }
    
    private function detectMethod(){
        return false;
    }
    
    private function parseGet(){
        foreach($_GET as $k=>$v){
            $this->data[$k] = $v;
        }
    }
    
    private function parsePost(){
        foreach($_POST as $k=>$v){
            $this->data[$k] = $v;
        }
    }
    
    private function parseJSON(){
        $json = file_get_contents('php://input');
        $json = json_decode($json, true);
        $keys = array_keys($json);
        if(empty($keys) || is_numeric($keys[0])){
            $json = current($json);
        }
        foreach ($json as $k=>$v){
            $this->data[$k] = $v;
        }
    }

    public function get($key = '') {
        return ($key == '' ? $this->data : (isset($this->data[$key]) ? $this->data[$key] : NULL));
    }

    public function getMethod() {
        return ($this->method);
    }

    public function getNum($key) {
        return (isset($this->data[$key]) && is_numeric($this->data[$key]) ? (int)$this->data[$key] : NULL);
    }

    public function getVar($key) {
        return $this->get($key);
    }

    public function hasData() {
        return (!empty($this->data));
    }
    
    
}

/*
 * IRequest interface which outlines the functions every Request class must define.
 */

interface IRequest {
    public function getMethod(); /* This function returns the method currently employed by the Request Class*/
    public function hasData(); /* This function returns true if Request class has any data, otherwise false */
    
    public function get($key=''); /* This function can be used to get access to full data array or individual elements addressed by $key param */
        
    public function getNum($key); /* This function ensures that the asked $key is a numeric value and returns it */
    public function getVar($key); /* This function is alias of above mentioned get function. */
}

?>