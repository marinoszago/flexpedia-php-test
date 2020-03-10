<?php


require('../../system/objects/invoice.php');

class InvoiceService {
    public function __construct(){
        
    }

    public function getAllInvoices($dbConn, $data) {

        $invoice = new Invoice($dbConn);

        $query = "SELECT id, 
                        client, 
                        invoice_amount,
                        invoice_amount_plus_vat, 
                        vat_rate, invoice_status, 
                        invoice_date, 
                        created_at 
                 FROM " . $invoice->getTableName() . " 
                 
                 ORDER BY created_at DESC";

        $stmt = $dbConn->prepare($query);

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
                        ORDER BY created_at DESC";

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
}

?>