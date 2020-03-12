<?php

class Invoice{
  
    // Connection instance
    private $connection;

    private $table_name = "invoices";

    // table columns
    public $id;
    public $client;
    public $invoice_amount;
    public $invoice_amount_plus_vat;
    public $vat_rate;
    public $invoice_status;
    public $invoice_date;
    public $created_at;

    public $total_invoiced;
    public $total_paid;
    public $total_outstanding;
  
    public function __construct($connection){
        $this->connection = $connection;
    }

    
    public function getTableName() {
        return $this->table_name;
    }


}
?>
