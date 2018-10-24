<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Domain Name Transaction object.  This is returned from the operations found in the Domain API.  It contains general status information about the transaction
 * along with an array of <b>DomainNameTransactionElement</b> objects for each Domain Name involved in the transaction.
 *
 *
 */
class DomainNameTransaction extends SerialisableObject {

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
     * @var mixed
     */
    protected $netistrarOrderId;

    /**
     * @var mixed
     */
    protected $netistrarOrderCurrency;

    /**
     * @var mixed
     */
    protected $netistrarOrderSubtotal;

    /**
     * @var mixed
     */
    protected $netistrarOrderTaxes;

    /**
     * @var mixed
     */
    protected $netistrarOrderTotal;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameTransactionElement[string]
     */
    protected $transactionElements;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameError
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
     * Get the netistrarOrderId
     *
     * @return mixed
     */
    public function getNetistrarOrderId(){
        return $this->netistrarOrderId;
    }

    /**
     * Get the netistrarOrderCurrency
     *
     * @return mixed
     */
    public function getNetistrarOrderCurrency(){
        return $this->netistrarOrderCurrency;
    }

    /**
     * Get the netistrarOrderSubtotal
     *
     * @return mixed
     */
    public function getNetistrarOrderSubtotal(){
        return $this->netistrarOrderSubtotal;
    }

    /**
     * Get the netistrarOrderTaxes
     *
     * @return mixed
     */
    public function getNetistrarOrderTaxes(){
        return $this->netistrarOrderTaxes;
    }

    /**
     * Get the netistrarOrderTotal
     *
     * @return mixed
     */
    public function getNetistrarOrderTotal(){
        return $this->netistrarOrderTotal;
    }

    /**
     * Get the transactionElements
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameTransactionElement[string]
     */
    public function getTransactionElements(){
        return $this->transactionElements;
    }

    /**
     * Get the transactionError
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameError
     */
    public function getTransactionError(){
        return $this->transactionError;
    }


}