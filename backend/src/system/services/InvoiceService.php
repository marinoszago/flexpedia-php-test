<?php


require('../../system/objects/invoice.php');

/**
 * InvoiceService contains those functions that fetch data from database
 * All of them use a database connection as a parameter and the data which required to perform the actions
 */

class InvoiceService {
    public function __construct(){
        
    }

    public function getRowCount($dbConn) {
        $invoice = new Invoice($dbConn);    
        
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

    public function getInvoices($dbConn, $data) {

        $invoice = new Invoice($dbConn);
        
        if(isset($data["startRow"]) && isset($data["fetchCount"]) && isset($data["filter"]) && $data["filter"] != ""){
                $keyword = $data["filter"];

                $query = "SELECT id, 
                        client, 
                        invoice_amount,
                        invoice_amount_plus_vat, 
                        vat_rate, invoice_status, 
                        invoice_date, 
                        created_at 
                 FROM " . $invoice->getTableName() . " 
                 WHERE client LIKE CONCAT('%', :keyword, '%')
                 ORDER BY created_at ASC
                 LIMIT :fetchCount OFFSET :startRow";

            $stmt = $dbConn->prepare($query);
                
            $stmt->bindParam(":fetchCount", $data["fetchCount"],PDO::PARAM_INT);
            $stmt->bindParam(":startRow", $data["startRow"],PDO::PARAM_INT);
            $stmt->bindParam(":keyword", $keyword,PDO::PARAM_STR);

        }if(isset($data["startRow"]) && isset($data["fetchCount"]) && (!isset($data["filter"]) || $data["filter"] == "")){
            $query = "SELECT id, 
                        client, 
                        invoice_amount,
                        invoice_amount_plus_vat, 
                        vat_rate, invoice_status, 
                        invoice_date, 
                        created_at 
                 FROM " . $invoice->getTableName() . " 
                 ORDER BY created_at ASC
                 LIMIT :fetchCount OFFSET :startRow";

            $stmt = $dbConn->prepare($query);

            $stmt->bindParam(":fetchCount", $data["fetchCount"],PDO::PARAM_INT);
            $stmt->bindParam(":startRow", $data["startRow"],PDO::PARAM_INT);

        }else if($data["filter"] == ""){
            $query = "SELECT id, 
                        client, 
                        invoice_amount,
                        invoice_amount_plus_vat, 
                        vat_rate, invoice_status, 
                        invoice_date, 
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
                    "client" => $client,
                    "invoice_amount" => $invoice_amount,
                    "invoice_amount_plus_vat" => $invoice_amount_plus_vat,
                    "vat_rate" => $vat_rate,
                    "invoice_status" => $invoice_status,
                    "invoice_date" => $invoice_date,
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
    public function getInvoice($dbConn, $data) {

        $invoice = new Invoice($dbConn);

        $query = "SELECT id, 
                        client, 
                        invoice_amount,
                        invoice_amount_plus_vat, 
                        vat_rate, 
                        invoice_status, 
                        invoice_date, 
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
                    "client" => $client,
                    "invoice_amount" => $invoice_amount,
                    "invoice_amount_plus_vat" => $invoice_amount_plus_vat,
                    "vat_rate" => $vat_rate,
                    "invoice_status" => $invoice_status,
                    "invoice_date" => $invoice_date,
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

    public function insertInvoice($dbConn, $data) {
        $invoice = new Invoice($dbConn);

        if(isset($data["client"]) 
            && isset($data["invoice_amount"]) 
            && isset($data["invoice_amount_plus_vat"]) 
            && isset($data["vat_rate"]) 
            && isset($data["invoice_status"]) 
            && isset($data["invoice_date"]) 
            )
        {
            $query = "INSERT INTO " .$invoice->getTableName()."
                    ( 
                        client, 
                        invoice_amount,
                        invoice_amount_plus_vat, 
                        vat_rate, 
                        invoice_status, 
                        invoice_date, 
                        created_at 
                    )
                    VALUES
                    (
                        :client,
                        :invoice_amount,
                        :invoice_amount_plus_vat,
                        :vat_rate,
                        :invoice_status,
                        :invoice_date,
                        :created_at
                    )";
            $stmt = $dbConn->prepare($query);

            $date = new DateTime();
            $date = $date->format('Y-m-d H:i:s');

            $stmt->bindParam(':client', $data["client"], PDO::PARAM_STR);
            $stmt->bindParam(':invoice_amount', $data["invoice_amount"]);
            $stmt->bindParam(':invoice_amount_plus_vat', $data["invoice_amount_plus_vat"]);
            $stmt->bindParam(':vat_rate', $data["vat_rate"]);
            $stmt->bindParam(':invoice_status', $data["invoice_status"], PDO::PARAM_STR);
            $stmt->bindParam(':invoice_date', $data["invoice_date"], PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $date);

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

    public function updateInvoice($dbConn, $data) {
        $invoice = new Invoice($dbConn);
        
        if(isset($data["id"]) && ($data["id"] > 0)
            && isset($data["client"]) 
            && isset($data["invoice_amount"]) 
            && isset($data["invoice_amount_plus_vat"]) 
            && isset($data["vat_rate"]) 
            && isset($data["invoice_status"]) 
            && isset($data["invoice_date"]) 
            )
        {
            $query = "UPDATE ".$invoice->getTableName()." SET 
                    client = :client,
                    invoice_amount = :invoice_amount,
                    invoice_amount_plus_vat = :invoice_amount_plus_vat,
                    vat_rate = :vat_rate,
                    invoice_status = :invoice_status,
                    invoice_date = :invoice_date
                WHERE id = :id";

            $stmt = $dbConn->prepare($query);

            $stmt->bindParam(':id', $data["id"]);
            $stmt->bindParam(':client', $data["client"]);
            $stmt->bindParam(':invoice_amount', $data["invoice_amount"]);
            $stmt->bindParam(':invoice_amount_plus_vat', $data["invoice_amount_plus_vat"]);
            $stmt->bindParam(':vat_rate', $data["vat_rate"]);
            $stmt->bindParam(':invoice_status', $data["invoice_status"]);
            $stmt->bindParam(':invoice_date', $data["invoice_date"]);

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

    public function deleteInvoice($dbConn, $data) {
        $invoice = new Invoice($dbConn);
        
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
        $invoice = new Invoice($dbConn);

        $query = "SELECT id, 
                        client, 
                        invoice_amount_plus_vat
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

            fputcsv($output, array('Invoice ID', 'Company Name', 'Invoice Amount'));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
                extract($row);
          
                $invoice_item=array(
                    "id" => $id,
                    "client" => $client,
                    "invoice_amount_plus_vat" => $invoice_amount_plus_vat
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

    public function exportToCsvCustomerReport($dbConn, $data = null) {
        $invoice = new Invoice($dbConn);

        $query = "SELECT client, SUM(invoice_amount) as total_invoiced, 
                SUM(CASE WHEN invoice_status='paid' THEN invoice_amount_plus_vat ELSE 0 END) as total_paid, 
                SUM(CASE WHEN invoice_status='unpaid' THEN invoice_amount_plus_vat ELSE 0 END) as total_outstanding 
                FROM ".$invoice->getTableName()." 
                GROUP BY client";
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

            fputcsv($output, array('Comapny Name', 'Total Invoiced Amount', 'Total Amount Paid', 'Total Amount Outstanding'));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
                extract($row);
          
                $invoice_item=array(
                    "client" => $client,
                    "total_invoiced" => $total_invoiced,
                    "total_paid" => $total_paid,
                    "total_outstanding" => $total_outstanding
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