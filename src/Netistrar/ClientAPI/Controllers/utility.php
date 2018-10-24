<?php

namespace Netistrar\ClientAPI\Controllers;

use Kinikit\Core\Util\HTTP\WebServiceProxy;

/**
 * The Utility API provides utility services in support of the other APIs
 *
 * 
*/
class utility extends WebServiceProxy {

    /**
     * Ping function for checking API service availability.  Returns OK if successful
     *
     * 
     * @return string
     */
    public function ping(){
        return parent::callMethod("ping", "GET", array(),null,"string");
    }

    /**
     * Create a bulk operation progress key.  This can then be passed to a bulk operation method in e.g. the Domain API to allow progress tracking of the operation
     * using the getBulkOperationProgress method.
     *
     * 
     * @return string
     */
    public function createBulkOperation(){
        return parent::callMethod("bulkoperation", "POST", array(),null,"string");
    }

    /**
     * Get the progress for a bulk operation as a BulkOperationProgress object using a progress key generated using the createBulkOperation method.  This will typically be attached to an operation in
     * e.g. the Domain API after which repeated calls can be made asynchronously to this method to check progress.
     *
     * 
     * @param string $bulkOperationProgressKey
     * @return \Netistrar\ClientAPI\Objects\Utility\BulkOperationProgress
     */
    public function getBulkOperationProgress($bulkOperationProgressKey){
        return parent::callMethod("bulkoperation/$bulkOperationProgressKey", "GET", array(),null,"\Netistrar\ClientAPI\Objects\Utility\BulkOperationProgress");
    }


}

