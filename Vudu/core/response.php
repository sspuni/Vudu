<?php

/*
 * Name   : response.php
 * Author : Sandeep Singh
 * 
 */

class Response implements IResponse{
    private $data;
    private $format;


    public function __construct($format='json') {
        $this->data = array();
        $this->format = strtolower($format);
    }
    
    public function setError($code, $msg) {
        if(!isset($this->data['error'])) $this->data['error'] = array();
        $this->data['error'][] = array('code'=>$code, 'message'=>$msg);
        $this->setStatus('failed');
    }
    
    private function setStatus($status) {
        $this->data['status'] = $status;
    }
    
    public function setResponse($resp) {
        $this->data['response'] = $resp;
        $this->setStatus('success');
    }

    public function doOutput() {
        echo $this->getOutput();
    }

    public function getOutput() {
        switch($this->format){
            case 'json':
                $out = $this->toJSON();
                break;
            case 'xml':
                $out = $this->toXML();
                break;
            default:
                $this->setFormat('json');
                $out = $this->toJSON();
                
        }
        
        return $out;
    }

    public function setFormat($format) {
        $this->format = strtolower($format);
    }

    public function getFormat() {
        return $this->format;
    }
    
    private function toJSON(){
        return json_encode($this->data);
    }
    
    private function toXML(){
        return xmlrpc_encode($this->data);
    }
}

interface IResponse {
    public function setError($code, $msg);
    public function setResponse($resp);
    public function getOutput();
    public function doOutput();
    public function setFormat($format);
    public function getFormat();
}

?>