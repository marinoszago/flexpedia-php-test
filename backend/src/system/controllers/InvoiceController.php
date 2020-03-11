<?php

require('../../system/services/InvoiceService.php');

class InvoiceController{


    //GET
    public function get($getArray, $dbConn) {

        $dataAction = $getArray["dataAction"];

        $invoiceService = new InvoiceService();
        $invoiceService->$dataAction($dbConn, $getArray);
    }
    
    //POST
    public function post($dataAction) {
        
    }

    //DELETE
    public function delete($dataAction) {
        
    }




}
?>