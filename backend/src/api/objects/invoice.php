<?php
class Invoice{
  
    // database connection and table name
    private $conn;
    private $table_name = "invoices";
  
    // object properties
    public $id;
    public $client;
    public $invoice_amount;
    public $invoice_amount_plus_vat;
    public $vat_rate;
    public $invoice_status;
    public $invoice_date;
    public $created_at;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read invoices
    function read(){
    
        // select all query
        $query = "SELECT
                    id, client, invoice_amount, invoice_amount_plus_vat,vat_rate,invoice_status,invoice_date,created_at
                FROM
                    " . $this->table_name . " 
                    
                ORDER BY
                    created_at DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}
?>