<?php

/*
 * Name : auth.php
 * Author : Sandeep Singh
 * Description : AuthController is responsible for handling authorization logic.
 */

class AuthController {
    private $isAuthorized = false;
    private $authInfo = null;
    
    function __construct() {
        
        $this->reset();
        
    }
    
    public function reset(){
        $authkey = Vudu::$request->getVar('authkey');
        if(empty($authkey)) return false;
        
        session_id($authkey);
        session_start();
        
        if(isset($_SESSION['authid'])){
            $this->authInfo =  array('authid'=>$_SESSION['authid']);
            $this->isAuthorized = true;
        }
        return true;
    }
    
    public function __get($name) {
        switch($name){
            case 'isAuthorized':
                return $this->isAuthorized;
            case 'authInfo':
                return $this->authInfo;
        }
    }
    
    public function __set($name, $value) {
        switch($name){
            case 'authInfo':
                if(!empty($value)){
                    $this->authInfo = $value;
                    $this->isAuthorized = true;
                }else {
                    $this->authInfo = null;
                    $this->isAuthorized = false;
                }
                break;
        }
    }
    
    public function makeAuth($authid){
        session_start();
        session_regenerate_id(true);
        $_SESSION['authid'] = $authid;
        return session_id();
    }
}

?>