<?php


require('../../system/objects/invoiceItem.php');

class InvoiceItemService {
    public function __construct(){
        
    }

    public function getRowCount($dbConn) {
        $invoice = new InvoiceItem($dbConn);    
        
        $query = "SELECT COUNT(*) AS ROW_COUNT
                 FROM " . $invoice->getTableName(). "";

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
                array("message" => "No invoices found.")
            );
        } 

    }

    public function getInvoiceItems($dbConn, $data) {

        $invoice = new InvoiceItem($dbConn);
        
        if(isset($data["startRow"]) && isset($data["fetchCount"]) && isset($data["filter"]) && $data["filter"] != ""){
                $keyword = $data["filter"];

                $query = "SELECT id, 
                            invoice_id,
                            name,
                            amount,
                            created_at
                 FROM " . $invoice->getTableName() . " 
                 WHERE name LIKE CONCAT('%', :keyword, '%')
                 ORDER BY created_at ASC
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
                 FROM " . $invoice->getTableName() . " 
                 ORDER BY created_at ASC
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
                 FROM " . $invoice->getTableName() . " 
                 
                 ORDER BY created_at ASC";

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
                array("message" => "No invoices found.")
            );
        } 


    }

    //PDO encapsulate bind  param
    public function getInvoiceItem($dbConn, $data) {

        $invoice = new InvoiceItem($dbConn);

        $query = "SELECT id, 
                    invoice_id,
                    name,
                    amount,
                    created_at
                FROM " . $invoice->getTableName() . " 
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
                array("message" => "No invoices found.")
            );
        } 
    
    
    }

    public function insertInvoiceItem($dbConn, $data) {
        $invoice = new InvoiceItem($dbConn);

        if(isset($data["name"]) 
            && isset($data["invoice_id"]) 
            && isset($data["amount"]) 
            && isset($data["created_at"]) 
        )
        {
            $query = "INSERT INTO " .$invoice->getTableName()."
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

            $stmt->bindParam(':name', $data["name"], PDO::PARAM_STR);
            $stmt->bindParam(':invoice_id', $data["invoice_id"]);
            $stmt->bindParam(':amount', $data["amount"]);
            $stmt->bindParam(':created_at', $data["created_at"]);

            $stmt->execute();

            // set response code - 200 OK
            http_response_code(200);
            
            // show products data in json format
            echo json_encode(
                array("message" => "Invoice insert success")
            );
        }else{
            
            // set response code - 404 Not found
            http_response_code(404);
            
            // tell the user no products found
            echo json_encode(
                array("message" => "Error: Unsuccessful insert of invoice. Please check the data again")
            );
        } 
    }

    public function updateInvoiceItem($dbConn, $data) {
        $invoice = new InvoiceItem($dbConn);
        
        if(isset($data["id"]) && ($data["id"] > 0)
            && isset($data["name"]) 
            && isset($data["invoice_id"]) 
            && isset($data["amount"]) 
            && isset($data["created_at"]) 
        )
        {
            $query = "UPDATE ".$invoice->getTableName()." SET 
                    name = :name,
                    invoice_id = :invoice_id,
                    amount = :amount,
                    created_at = :created_at
                WHERE id = :id";

            $stmt = $dbConn->prepare($query);

            $stmt->bindParam(':id', $data["id"]);
            $stmt->bindParam(':name', $data["name"], PDO::PARAM_STR);
            $stmt->bindParam(':invoice_id', $data["invoice_id"]);
            $stmt->bindParam(':amount', $data["amount"]);
            $stmt->bindParam(':created_at', $data["created_at"], PDO::PARAM_STR);

            $stmt->execute();

            // set response code - 200 OK
            http_response_code(200);
            
            // show products data in json format
            echo json_encode(
                array("message" => "Invoice update success")
            );
        }else{
            
            // set response code - 404 Not found
            http_response_code(404);
            
            // tell the user no products found
            echo json_encode(
                array("message" => "Error: Unsuccessful update of invoice. Please check the data again")
            );
        } 
    }

    public function deleteInvoiceItem($dbConn, $data) {
        $invoice = new InvoiceItem($dbConn);
        
        if(isset($data["id"]))
        {
            $query = "DELETE FROM ".$invoice->getTableName()."
                WHERE id = :id";

            $stmt = $dbConn->prepare($query);

            $stmt->bindParam(':id', $data["id"]);

            $stmt->execute();

            // set response code - 200 OK
            http_response_code(200);
            
            // show products data in json format
            echo json_encode(
                array("message" => "Invoice delete success")
            );
        }else{
            
            // set response code - 404 Not found
            http_response_code(404);
            
            // tell the user no products found
            echo json_encode(
                array("message" => "Error: Unsuccessful delete of invoice. Please check the data again")
            );
        } 
    }

    public function exportToCsv($dbConn, $data = null) {
        $invoice = new InvoiceItem($dbConn);

        $query = "SELECT id, 
                        invoice_id, 
                        name,
                        amount,
                        created_at
                 FROM " . $invoice->getTableName() . " 
                 
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

            fputcsv($output, array('Invoice Item ID', 'Invoice ID', 'Name', 'Amount', 'Created At'));
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
                array("message" => "No invoices found.")
            );
        } 
    }
}

?>