<?php

require '../interfaces/RequestInterface.php';

class Invoice implements RequestInterface{
  
    // Connection instance
    private $connection;

    // table columns
    public $id;
    public $client;
    public $invoice_amount;
    public $invoice_amount_plus_vat;
    public $vat_rate;
    public $invoice_status;
    public $invoice_date;
    public $created_at;
  
    public function __construct($connection){
        $this->connection = $connection;
    }

    public function request($query){

        $stmt = $this->connection->prepare($query);

        $stmt->execute();

        return $stmt;
    }

}
?>
