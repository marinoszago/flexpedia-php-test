<?php

require_once "../services/InvoiceService.php";

class InvoiceController{

  
    //GET
    public function get($dataAction, $dataObj, $dbConn) {
        $invoiceService = new InvoiceService();
        $invoiceService->$dataAction($dbConn, $dataObj);
    }
    
    //POST
    public function post($dataAction) {
        
    }

    //DELETE
    public function delete($dataAction) {
        
    }




}
?>