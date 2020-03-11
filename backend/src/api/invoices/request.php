<?php

// px ena request obj pou na dexete diafora
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
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
            array("message" => "Invalid request. No data action specified")
        );

        return 0;
    }

    $getArray = array();
    $getArray = $_GET;

    $stmt = $invoiceController->get($getArray, $db);

    
}else if($request_type == "POST"){

}else if($request_type == "DELETE"){
    
}else {
    http_response_code(404);
      
    // tell the user no products found
    echo json_encode(
        array("message" => "Invalid request!")
    );
}
  
