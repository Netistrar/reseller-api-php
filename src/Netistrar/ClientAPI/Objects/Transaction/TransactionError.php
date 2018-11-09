<?php

namespace Netistrar\ClientAPI\Objects\Transaction;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Domain name error object
 */
class TransactionError extends SerialisableObject {

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var mixed[string]
     */
    protected $additionalInfo;

    /**
     * @var \Netistrar\ClientAPI\Objects\Transaction\TransactionError[string]
     */
    protected $relatedErrors;



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
     * Get the code
     *
     * @return string
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * Get the message
     *
     * @return string
     */
    public function getMessage(){
        return $this->message;
    }

    /**
     * Get the additionalInfo
     *
     * @return mixed[string]
     */
    public function getAdditionalInfo(){
        return $this->additionalInfo;
    }

    /**
     * Get the relatedErrors
     *
     * @return \Netistrar\ClientAPI\Objects\Transaction\TransactionError[string]
     */
    public function getRelatedErrors(){
        return $this->relatedErrors;
    }


}