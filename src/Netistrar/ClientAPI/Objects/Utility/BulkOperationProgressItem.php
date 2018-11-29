<?php

namespace Netistrar\ClientAPI\Objects\Utility;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Encodes progress information about a single element within a bulk operation.  These are returned as part of the <a href="bulk-operation-progress">BulkOperationProgress</a> object as subordinate objects for e.g. use in GUIs to
 * update the progress as it completes.
 *
 */
class BulkOperationProgressItem extends SerialisableObject {

    /**
     * @var string
     */
    protected $title;

    /**
     * @var float
     */
    protected $progressPercentage;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var \Netistrar\ClientAPI\Objects\Transaction\TransactionError[string]
     */
    protected $failureErrors;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the title
     *
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * Get the progressPercentage
     *
     * @return float
     */
    public function getProgressPercentage(){
        return $this->progressPercentage;
    }

    /**
     * Get the status
     *
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * Get the failureErrors
     *
     * @return \Netistrar\ClientAPI\Objects\Transaction\TransactionError[string]
     */
    public function getFailureErrors(){
        return $this->failureErrors;
    }


}