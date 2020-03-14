<?php

class InvoiceItem{
  
    // Connection instance
    private $connection;

    private $table_name = "invoice_items";

    // table columns
    public $id;
    public $invoice_id;
    public $name;
    public $amount;
    public $created_at;
  
    public function __construct($connection){
        $this->connection = $connection;
    }

    
    public function getTableName() {
        return $this->table_name;
    }


}
?>
