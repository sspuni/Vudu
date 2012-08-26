<?php

/*
 * Name : authfilter.php
 * Author : Sandeep Singh
 * Description : Its a filter which can be used in any controller as a before filter to ensure that the user requesting for controller's action is authrosized or not.
 */

class AuthFilter {
    
    public function run(){
        if(!Vudu::$auth->isAuthorized){
            Vudu::$response->setError(403, "You don't have access to this content, please log in first.");
            return false;
        }
        return true;
    }
}

?>