<?php

namespace Netistrar\ClientAPI\Objects\Transaction;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Transaction element object.  Represents a single domain name result within a transaction returned from the operations found in the Domain API.
 *
 */
class TransactionElement extends SerialisableObject {

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $elementStatus;

    /**
     * @var mixed[string]
     */
    protected $operationData;

    /**
     * @var \Netistrar\ClientAPI\Objects\Transaction\TransactionError[string]
     */
    protected $elementErrors;

    /**
     * @var float
     */
    protected $orderLineSubtotal;

    /**
     * @var float
     */
    protected $orderLineTaxes;

    /**
     * @var float
     */
    protected $orderLineTotal;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType(){
        return $this->type;
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * Get the elementStatus
     *
     * @return string
     */
    public function getElementStatus(){
        return $this->elementStatus;
    }

    /**
     * Get the operationData
     *
     * @return mixed[string]
     */
    public function getOperationData(){
        return $this->operationData;
    }

    /**
     * Get the elementErrors
     *
     * @return \Netistrar\ClientAPI\Objects\Transaction\TransactionError[string]
     */
    public function getElementErrors(){
        return $this->elementErrors;
    }

    /**
     * Get the orderLineSubtotal
     *
     * @return float
     */
    public function getOrderLineSubtotal(){
        return $this->orderLineSubtotal;
    }

    /**
     * Get the orderLineTaxes
     *
     * @return float
     */
    public function getOrderLineTaxes(){
        return $this->orderLineTaxes;
    }

    /**
     * Get the orderLineTotal
     *
     * @return float
     */
    public function getOrderLineTotal(){
        return $this->orderLineTotal;
    }


}