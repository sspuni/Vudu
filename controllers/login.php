<?php

class LoginController extends Controller {
    public function index() {
        
        $uid = Vudu::$request->getVar('email');
        $pass = Vudu::$request->getVar('password');
        
        $uid = Vudu::$database->escape($uid);
        $pass = Vudu::$database->escape($pass);
        
        $pass = md5($pass);
        
        
        $userid = Vudu::$database->getColumn("SELECT userid FROM users WHERE email='$uid' AND passwd='$pass'");
        
        if(!empty($userid)){
            $auth = Vudu::$auth->makeAuth($userid);
            Vudu::$response->setResponse(array("authkey"=>$auth, "message"=>"Successfully Logged in."));
        }else {
            Vudu::$response->setError(201, "Login Failed, please try again.");
        }
    }
}


?>