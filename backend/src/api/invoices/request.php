<?php

// px ena request obj pou na dexete diafora
// required headers
header("Content-Type: application/json; charset=UTF-8");
header('Accept: application/json');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, PATCH");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}
  
// database connection will be here
// include database and object files

require('../../system/database/DatabaseConnection.php');
require('../../system/controllers/InvoiceController.php');
  
// instantiate database and invoice object
$databaseInstance = DatabaseConnection::getInstance();
$db = $databaseInstance->getConnection();
  
// initialize object
$invoiceController = new InvoiceController($db);

//maybe send the data action 
$request_type = $_SERVER['REQUEST_METHOD'];

if ($request_type == "GET"){
    

    if (!isset($_GET) && !isset($_GET["dataAction"])){
        http_response_code(404);
      
        // tell the user no products found
        echo json_encode(
            array("message" => "405 Invalid request. No data action specified")
        );

        return 0;
    }

    $getArray = array();
    $getArray = $_GET;

    $stmt = $invoiceController->get($getArray, $db);

    
}else if($request_type == "POST"){
    
    if (!isset($_POST) && !isset($_POST["dataAction"])){
        http_response_code(404);
      
        // tell the user no products found
        echo json_encode(
            array("message" => "405 Invalid request. No data action specified")
        );

        return 0;
    }

    $postArray = array();
    $postArray = $_POST;

    $stmt = $invoiceController->post($postArray, $db);

}else if($request_type == "DELETE"){
    $_DELETE = file_get_contents('php://input');

    if (!isset($_DELETE) && !isset($_DELETE["dataAction"])){
        http_response_code(404);
      
        // tell the user no products found
        echo json_encode(
            array("message" => "405 Invalid request. No data action specified")
        );

        return 0;
    }

    $deleteArray = array();
    $deleteArray = json_decode($_DELETE, TRUE);

    $stmt = $invoiceController->delete($deleteArray, $db);
    
}else if($request_type == "PATCH"){
    $_PATCH = file_get_contents('php://input');

    if (!isset($_PATCH) && !isset($_PATCH["dataAction"])){
        http_response_code(404);
      
        // tell the user no products found
        echo json_encode(
            array("message" => "405 Invalid request. No data action specified")
        );

        return 0;
    }
    
    $patchArray = array();
    $patchArray = json_decode($_PATCH, TRUE);

    $stmt = $invoiceController->patch($patchArray, $db);
}else {
    http_response_code(404);
      
    // tell the user no products found
    echo json_encode(
        array("message" => "405 Invalid request. Method not allowed")
    );
}
  
