<?php


require('../../system/objects/invoiceItem.php');
require('../../system/objects/invoice.php');

/**
 * InvoiceItemService contains those functions that fetch data from database
 * All of them use a database connection as a parameter and the data which required to perform the actions
 */

class InvoiceItemService {
    public function __construct(){
        
    }

    public function getRowCount($dbConn) {
        $invoiceItem = new InvoiceItem($dbConn);    
        
        $query = "SELECT COUNT(*) AS ROW_COUNT
                 FROM " . $invoiceItem->getTableName(). "";

        $stmt = $dbConn->prepare($query);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result["ROW_COUNT"] > 0){
          
            // set response code - 200 OK
            http_response_code(200);
          
            // show products data in json format
            echo json_encode(array("data" => $result["ROW_COUNT"]));
        }else{
          
            // set response code - 404 Not found
            http_response_code(404);
          
            // tell the user no products found
            echo json_encode(
                array("message" => "No invoice items found.")
            );
        } 

    }

    public function getInvoiceItems($dbConn, $data) {

        $invoiceItem = new InvoiceItem($dbConn);
        
        if(isset($data["startRow"]) && isset($data["fetchCount"]) && isset($data["filter"]) && $data["filter"] != ""){
                $keyword = $data["filter"];

                $query = "SELECT id, 
                            invoice_id,
                            name,
                            amount,
                            created_at
                 FROM " . $invoiceItem->getTableName() . " 
                 WHERE name LIKE CONCAT('%', :keyword, '%')
                 ORDER BY id, created_at ASC
                 LIMIT :fetchCount OFFSET :startRow";

            $stmt = $dbConn->prepare($query);
                
            $stmt->bindParam(":fetchCount", $data["fetchCount"],PDO::PARAM_INT);
            $stmt->bindParam(":startRow", $data["startRow"],PDO::PARAM_INT);
            $stmt->bindParam(":keyword", $keyword,PDO::PARAM_STR);

        }if(isset($data["startRow"]) && isset($data["fetchCount"]) && (!isset($data["filter"]) || $data["filter"] == "")){
            $query = "SELECT id, 
                        invoice_id,
                        name,
                        amount,
                        created_at 
                 FROM " . $invoiceItem->getTableName() . " 
                 ORDER BY id, created_at ASC
                 LIMIT :fetchCount OFFSET :startRow";

            $stmt = $dbConn->prepare($query);

            $stmt->bindParam(":fetchCount", $data["fetchCount"],PDO::PARAM_INT);
            $stmt->bindParam(":startRow", $data["startRow"],PDO::PARAM_INT);

        }else if($data["filter"] == ""){
            $query = "SELECT id, 
                        invoice_id,
                        name,
                        amount,
                        created_at
                 FROM " . $invoiceItem->getTableName() . " 
                 
                 ORDER BY id, created_at ASC";

            $stmt = $dbConn->prepare($query);
        }

        $stmt->execute();

        $num = $stmt->rowCount();
      
        // check if more than 0 record found
        if($num>0){
          
            $invoices_arr=array();
            $invoices_arr["data"]=array();
          
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
                extract($row);
          
                $invoice_item=array(
                    "id" => $id,
                    "invoice_id" => $invoice_id,
                    "name" => $name,
                    "amount" => $amount,
                    "created_at" => $created_at
                );
          
                array_push($invoices_arr["data"], $invoice_item);
            }
          
            // set response code - 200 OK
            http_response_code(200);
          
            // show products data in json format
            echo json_encode($invoices_arr);
        }else{
          
            // set response code - 404 Not found
            http_response_code(404);
          
            // tell the user no products found
            echo json_encode(
                array("message" => "No invoice items found.")
            );
        } 


    }

    //PDO encapsulate bind  param
    public function getInvoiceItem($dbConn, $data) {

        $invoiceItem = new InvoiceItem($dbConn);

        $query = "SELECT id, 
                    invoice_id,
                    name,
                    amount,
                    created_at
                FROM " . $invoiceItem->getTableName() . " 
                    WHERE id = :id
                    ORDER BY created_at ASC";

        $stmt = $dbConn->prepare($query);

        $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);

        $stmt->execute();

        $num = $stmt->rowCount();
        
        // check if more than 0 record found
        if($num>0){
            
            $invoices_arr=array();
            $invoices_arr["data"]=array();
            
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
                extract($row);
            
                $invoice_item=array(
                    "id" => $id,
                    "invoice_id" => $invoice_id,
                    "name" => $name,
                    "amount" => $amount,
                    "created_at" => $created_at
                );
            
                array_push($invoices_arr["data"], $invoice_item);
            }
            
            // set response code - 200 OK
            http_response_code(200);
            
            // show products data in json format
            echo json_encode($invoices_arr);
        }else{
            
            // set response code - 404 Not found
            http_response_code(404);
            
            // tell the user no products found
            echo json_encode(
                array("message" => "No invoice items found.")
            );
        } 
    
    
    }

    public function insertInvoiceItem($dbConn, $data) {
        $invoiceItem = new InvoiceItem($dbConn);

        if(isset($data["name"]) 
            && isset($data["invoice_id"]) 
            && isset($data["amount"]) 
        )
        {
            $query = "INSERT INTO " .$invoiceItem->getTableName()."
                    ( 
                        name, 
                        invoice_id,
                        amount, 
                        created_at
                        
                    )
                    VALUES
                    (
                        :name,
                        :invoice_id,
                        :amount,
                        :created_at
                        
                    )";
            $stmt = $dbConn->prepare($query);

            $date = new DateTime();
            $date = $date->format('Y-m-d H:i:s');

            $stmt->bindParam(':name', $data["name"], PDO::PARAM_STR);
            $stmt->bindParam(':invoice_id', $data["invoice_id"]);
            $stmt->bindParam(':amount', $data["amount"]);
            $stmt->bindParam(':created_at', $date);

            $stmt->execute();

            // set response code - 200 OK
            http_response_code(200);
            
            // show products data in json format
            echo json_encode(
                array("message" => "Invoice item insert success")
            );
        }else{
            
            // set response code - 404 Not found
            http_response_code(404);
            
            // tell the user no products found
            echo json_encode(
                array("message" => "Error: Unsuccessful insert of invoice item. Please check the data again")
            );
        } 
    }

    public function updateInvoiceItem($dbConn, $data) {
        $invoiceItem = new InvoiceItem($dbConn);
        
        if(isset($data["id"]) && ($data["id"] > 0)
            && isset($data["name"]) 
            && isset($data["invoice_id"]) 
            && isset($data["amount"]) 
        )
        {
            $query = "UPDATE ".$invoiceItem->getTableName()." SET 
                    name = :name,
                    invoice_id = :invoice_id,
                    amount = :amount
                WHERE id = :id";

            $stmt = $dbConn->prepare($query);

            $stmt->bindParam(':id', $data["id"]);
            $stmt->bindParam(':name', $data["name"], PDO::PARAM_STR);
            $stmt->bindParam(':invoice_id', $data["invoice_id"]);
            $stmt->bindParam(':amount', $data["amount"]);

            $stmt->execute();

            // set response code - 200 OK
            http_response_code(200);
            
            // show products data in json format
            echo json_encode(
                array("message" => "Invoice item update success")
            );
        }else{
            
            // set response code - 404 Not found
            http_response_code(404);
            
            // tell the user no products found
            echo json_encode(
                array("message" => "Error: Unsuccessful update of invoice item. Please check the data again")
            );
        } 
    }

    public function deleteInvoiceItem($dbConn, $data) {
        $invoiceItem = new InvoiceItem($dbConn);
        
        if(isset($data["id"]))
        {
            $query = "DELETE FROM ".$invoiceItem->getTableName()."
                WHERE id = :id";

            $stmt = $dbConn->prepare($query);

            $stmt->bindParam(':id', $data["id"]);

            $stmt->execute();

            // set response code - 200 OK
            http_response_code(200);
            
            // show products data in json format
            echo json_encode(
                array("message" => "Invoice item delete success")
            );
        }else{
            
            // set response code - 404 Not found
            http_response_code(404);
            
            // tell the user no products found
            echo json_encode(
                array("message" => "Error: Unsuccessful delete of invoice item. Please check the data again")
            );
        } 
    }

    public function exportToCsv($dbConn, $data = null) {
        $invoiceItem = new InvoiceItem($dbConn);
        $invoice = new Invoice($dbConn);

        $query = "SELECT ".$invoiceItem->getTableName().".id, 
                        ".$invoiceItem->getTableName().".invoice_id, 
                        ".$invoice->getTableName().".client,
                        ".$invoiceItem->getTableName().".name,
                        ".$invoiceItem->getTableName().".amount,
                        ".$invoiceItem->getTableName().".created_at
                 FROM " . $invoiceItem->getTableName() . " 
                 LEFT JOIN ".$invoice->getTableName()." ON ".$invoice->getTableName().".id = ".$invoiceItem->getTableName().".invoice_id
                 ORDER BY id, created_at ASC";

        $stmt = $dbConn->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();
      
        // check if more than 0 record found
        if($num>0){
            
            $invoices_arr=array();
            $invoices_arr["data"]=array();
          
            header("Content-Type: text/csv");
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");

            $output = fopen("php://output", "w");

            fputcsv($output, array("Invoice Item ID", "Invoice ID", "Client name", "Invoice Item Name", "Invoice Item Amount", "Created At"));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
                extract($row);
          
                $invoice_item=array(
                    "id" => $id,
                    "invoice_id" => $invoice_id,
                    "client" => $client,
                    "name" => $name,
                    "amount" => $amount,
                    "created_at" => $created_at
                );
                
                array_push($invoices_arr["data"], $invoice_item);
                fputcsv($output, $invoice_item);
                
            }
            fclose($output);   
          
            // set response code - 200 OK
            http_response_code(200);

        }else{
          
            // set response code - 404 Not found
            http_response_code(404);
          
            // tell the user no products found
            echo json_encode(
                array("message" => "No invoice items found.")
            );
        } 
    }
}

?>