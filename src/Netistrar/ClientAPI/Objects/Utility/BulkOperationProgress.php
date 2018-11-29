<?php

namespace Netistrar\ClientAPI\Objects\Utility;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Encodes information about the current progress of a bulk operation.  This includes the status of all contained <a href="bulk-operation-progress-item">BulkOperationProgressItem</a> objects which make up the bulk operation as well as general information about the progress of the whole operation both as a percentage and as a number of completed items for use in progressively updating GUIs etc.
 *
 */
class BulkOperationProgress extends SerialisableObject {

    /**
     * @var string
     */
    protected $status;

    /**
     * @var integer
     */
    protected $totalNumberOfItems;

    /**
     * @var integer
     */
    protected $completedItems;

    /**
     * @var float
     */
    protected $percentageComplete;

    /**
     * @var boolean
     */
    protected $hasFailedItems;

    /**
     * @var \Netistrar\ClientAPI\Objects\Utility\BulkOperationProgressItem[]
     */
    protected $progressItems;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
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
     * Get the totalNumberOfItems
     *
     * @return integer
     */
    public function getTotalNumberOfItems(){
        return $this->totalNumberOfItems;
    }

    /**
     * Get the completedItems
     *
     * @return integer
     */
    public function getCompletedItems(){
        return $this->completedItems;
    }

    /**
     * Get the percentageComplete
     *
     * @return float
     */
    public function getPercentageComplete(){
        return $this->percentageComplete;
    }

    /**
     * Get the hasFailedItems
     *
     * @return boolean
     */
    public function getHasFailedItems(){
        return $this->hasFailedItems;
    }

    /**
     * Get the progressItems
     *
     * @return \Netistrar\ClientAPI\Objects\Utility\BulkOperationProgressItem[]
     */
    public function getProgressItems(){
        return $this->progressItems;
    }


}