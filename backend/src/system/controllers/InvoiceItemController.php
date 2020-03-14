<?php

require('../../system/services/InvoiceItemService.php');

/**
 * The InvoiceItemController class just handles the request and nothing else
 */
class InvoiceItemController{


    //GET
    public function get($getArray, $dbConn) {

        $dataAction = $getArray["dataAction"];

        $invoiceItemService = new InvoiceItemService();
        $invoiceItemService->$dataAction($dbConn, $getArray);
    }
    
    //POST
    public function post($postArray, $dbConn) {
        $dataAction = $postArray["dataAction"];

        $invoiceItemService = new InvoiceItemService();
        $invoiceItemService->$dataAction($dbConn, $postArray);
    }

    //PATCH
    public function patch($patchArray, $dbConn) {

        $dataAction = $patchArray["dataAction"];

        $invoiceItemService = new InvoiceItemService();
        $invoiceItemService->$dataAction($dbConn, $patchArray);
    }

    //DELETE
    public function delete($deleteArray, $dbConn) {
        $dataAction = $deleteArray["dataAction"];

        $invoiceItemService = new InvoiceItemService();
        $invoiceItemService->$dataAction($dbConn, $deleteArray);
    }




}
?>