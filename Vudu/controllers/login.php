<?php

class LoginController extends Controller {
    public function index() {
        global $request, $response;
        
        $uid = $request->getVar('userid');
        $pass = $request->getVar('password');
        
        
        
    }
}


?>