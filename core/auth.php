<?php

/**
 * AuthController is responsible for handling authorization logic.
 * 
 * This class handles the logic of creating auth sessions. It automatically checks for **authkey** variable in user request and initialize the session accordingly.
 * 
 * @class AuthController
 * @filesource auth.php
 * @author Sandeep Singh (s4nd33p@gmail.com) 
 */

class AuthController {
    
    /**
     * Tells if the user is authorized or not
     * @var boolean 
     */
    private $isAuthorized = false;
    
    /**
     * Holds the authorization info.
     * 
     * This is the custom application set information, which is set by using makeAuth() function of this class. It can hold anything, as per the application logic and needs.
     * @var mixed 
     */
    private $authInfo = null;
    
    
    /**
     * Class constructor to automatically reset the session and check for authentication information regarding present user.
     * 
     * @function __construct 
     */
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