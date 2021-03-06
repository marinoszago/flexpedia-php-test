<?php

require('../../system/services/InvoiceService.php');

/**
 * The InvoiceController class just handles the request and nothing else
 */

class InvoiceController{


    //GET
    public function get($getArray, $dbConn) {

        $dataAction = $getArray["dataAction"];

        $invoiceService = new InvoiceService();
        $invoiceService->$dataAction($dbConn, $getArray);
    }
    
    //POST
    public function post($postArray, $dbConn) {
        $dataAction = $postArray["dataAction"];

        $invoiceService = new InvoiceService();
        $invoiceService->$dataAction($dbConn, $postArray);
    }

    //PATCH
    public function patch($patchArray, $dbConn) {

        $dataAction = $patchArray["dataAction"];

        $invoiceService = new InvoiceService();
        $invoiceService->$dataAction($dbConn, $patchArray);
    }

    //DELETE
    public function delete($deleteArray, $dbConn) {
        $dataAction = $deleteArray["dataAction"];

        $invoiceService = new InvoiceService();
        $invoiceService->$dataAction($dbConn, $deleteArray);
    }




}
?>