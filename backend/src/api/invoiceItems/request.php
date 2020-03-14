<?php

header('Accept: application/json');
header("Content-Type: application/json; charset=UTF-8");

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, PATCH");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}
  

require('../../system/database/DatabaseConnection.php');
require('../../system/controllers/InvoiceItemController.php');
  
$databaseInstance = DatabaseConnection::getInstance();
$db = $databaseInstance->getConnection();
  
$invoiceItemController = new InvoiceItemController($db);

$request_type = $_SERVER['REQUEST_METHOD'];

/**
 * For each method that is sent with the request perform the respected action of the controller
 */

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

    $stmt = $invoiceItemController->get($getArray, $db);

    
}else if($request_type == "POST"){
    
    $_POST = file_get_contents('php://input');
    if (!isset($_POST) && !isset($_POST["dataAction"])){
        http_response_code(404);
      
        // tell the user no products found
        echo json_encode(
            array("message" => "405 Invalid request. No data action specified")
        );

        return 0;
    }

    $postArray = array();
    $postArray = json_decode($_POST, TRUE);

    $stmt = $invoiceItemController->post($postArray, $db);

}else if($request_type == "DELETE"){
    $_DELETE = file_get_contents('php://input');
    $_DELETE = json_decode($_DELETE, TRUE);
    
    if (!isset($_DELETE["source"]) && !isset($_DELETE["source"]["dataAction"])){
        http_response_code(404);
      
        // tell the user no products found
        echo json_encode(
            array("message" => "405 Invalid request. No data action specified")
        );

        return 0;
    }
    
    $deleteArray = array();
    $deleteArray = $_DELETE["source"];

    $stmt = $invoiceItemController->delete($deleteArray, $db);
    
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

    $stmt = $invoiceItemController->patch($patchArray, $db);
}else {
    http_response_code(404);
      
    // tell the user no products found
    echo json_encode(
        array("message" => "405 Invalid request. Method not allowed")
    );
}

$db = $databaseInstance->closeConnection();
  
