<?php

/*
 * Name   : controller.php
 * Author : Sandeep Singh
 * 
 */

class Controller {
    
    public function index() {
        global $request, $response;
        $in = $request->get();
        $response->setResponse($in);
    }
    
    public function test() {
        global $request, $response;
        $response->setResponse(array('foo'=>'bar', 'in'=>$request->get()));
    }
    
}

?>