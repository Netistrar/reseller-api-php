<?php

namespace Netistrar\ClientAPI\Objects\Transaction;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Domain Name Transaction object.  This is returned from the operations found in the Domain API.  It contains general status information about the transaction
 * along with an array of <b>DomainNameTransactionElement</b> objects for each Domain Name involved in the transaction.
 *
 *
 */
class Transaction extends SerialisableObject {

    /**
     * @var string
     */
    protected $transactionType;

    /**
     * @var string
     */
    protected $transactionDateTime;

    /**
     * @var string
     */
    protected $transactionStatus;

    /**
     * @var integer
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $orderCurrency;

    /**
     * @var float
     */
    protected $orderSubtotal;

    /**
     * @var float
     */
    protected $orderTaxes;

    /**
     * @var float
     */
    protected $orderTotal;

    /**
     * @var \Netistrar\ClientAPI\Objects\Transaction\TransactionElement[string]
     */
    protected $transactionElements;

    /**
     * @var \Netistrar\ClientAPI\Objects\Transaction\TransactionError
     */
    protected $transactionError;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the transactionType
     *
     * @return string
     */
    public function getTransactionType(){
        return $this->transactionType;
    }

    /**
     * Get the transactionDateTime
     *
     * @return string
     */
    public function getTransactionDateTime(){
        return $this->transactionDateTime;
    }

    /**
     * Get the transactionStatus
     *
     * @return string
     */
    public function getTransactionStatus(){
        return $this->transactionStatus;
    }

    /**
     * Get the orderId
     *
     * @return integer
     */
    public function getOrderId(){
        return $this->orderId;
    }

    /**
     * Get the orderCurrency
     *
     * @return string
     */
    public function getOrderCurrency(){
        return $this->orderCurrency;
    }

    /**
     * Get the orderSubtotal
     *
     * @return float
     */
    public function getOrderSubtotal(){
        return $this->orderSubtotal;
    }

    /**
     * Get the orderTaxes
     *
     * @return float
     */
    public function getOrderTaxes(){
        return $this->orderTaxes;
    }

    /**
     * Get the orderTotal
     *
     * @return float
     */
    public function getOrderTotal(){
        return $this->orderTotal;
    }

    /**
     * Get the transactionElements
     *
     * @return \Netistrar\ClientAPI\Objects\Transaction\TransactionElement[string]
     */
    public function getTransactionElements(){
        return $this->transactionElements;
    }

    /**
     * Get the transactionError
     *
     * @return \Netistrar\ClientAPI\Objects\Transaction\TransactionError
     */
    public function getTransactionError(){
        return $this->transactionError;
    }


}