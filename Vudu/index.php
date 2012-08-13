<?php

/*
 * Name        : index.php
 * Author      : Sandeep Singh
 * Email       : s4nd33p@gmail.com
 * Description : This is the heart of the Vudu Web Services Framework.
 * All requests for WS are handled by this file. It controls request/response, 
 * input/output, routing, controllers, etc. It initializes and call them in
 * correct order.
 * 
 *  */

include 'include/vudu.php';

$response = new Response('json');

$request = new Request("get");

$route = new Route();

$route->doAction();

$response->doOutput();

?>
