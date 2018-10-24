<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Transaction element object.  Represents a single domain name result within a transaction returned from the operations found in the Domain API.
 *
 */
class DomainNameTransactionElement extends SerialisableObject {

    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var string
     */
    protected $elementStatus;

    /**
     * @var mixed[string]
     */
    protected $operationData;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameError[string]
     */
    protected $elementErrors;

    /**
     * @var mixed
     */
    protected $netistrarOrderLineSubtotal;

    /**
     * @var mixed
     */
    protected $netistrarOrderLineTaxes;

    /**
     * @var mixed
     */
    protected $netistrarOrderLineTotal;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the domainName
     *
     * @return string
     */
    public function getDomainName(){
        return $this->domainName;
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
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameError[string]
     */
    public function getElementErrors(){
        return $this->elementErrors;
    }

    /**
     * Get the netistrarOrderLineSubtotal
     *
     * @return mixed
     */
    public function getNetistrarOrderLineSubtotal(){
        return $this->netistrarOrderLineSubtotal;
    }

    /**
     * Get the netistrarOrderLineTaxes
     *
     * @return mixed
     */
    public function getNetistrarOrderLineTaxes(){
        return $this->netistrarOrderLineTaxes;
    }

    /**
     * Get the netistrarOrderLineTotal
     *
     * @return mixed
     */
    public function getNetistrarOrderLineTotal(){
        return $this->netistrarOrderLineTotal;
    }


}