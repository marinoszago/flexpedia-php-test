<?php
require '../objects/Invoice.php';

class InvoiceController{

    private $table_name = "invoices";

    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }
  
    public function getAllInvoices() {

        $invoice = new Invoice($this->connection);

        $query = "SELECT id, client, invoice_amount,invoice_amount_plus_vat, vat_rate, invoice_status, invoice_date, created_at 
                    FROM " . $this->table_name . " ORDER BY created_at DESC";

        $stmt = $invoice->request($query);

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

        //return $invoices_arr;

    }

    //PDO encapsulate bind  parama
    public function getInvoice($id) {

        $invoice = new Invoice($this->connection);

        $query = "SELECT id, client, invoice_amount,invoice_amount_plus_vat, vat_rate, invoice_status, invoice_date, created_at 
                    FROM " . $this->table_name . " WHERE id = $id ORDER BY created_at DESC";

        $stmt = $invoice->request($query);

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

        //return $invoices_arr;

    }


}
?>