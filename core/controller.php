<?php

/*
 * Name   : controller.php
 * Author : Sandeep Singh
 * 
 */

class Controller {
    
    public $filters=array(
        'before' => array('AuthFilter'),
        'before_exceptions' => array('index')
        );
    
    public function index() {
        
        $in = Vudu::$request->get();
        Vudu::$response->setResponse($in);
    }
    
    public function test() {
        
        Vudu::$response->setResponse(array('foo'=>'bar', 'in'=>Vudu::$request->get()));
    }
    
}

?>