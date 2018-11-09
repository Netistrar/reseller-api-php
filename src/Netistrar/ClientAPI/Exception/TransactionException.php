<?php

namespace Netistrar\ClientAPI\Exception;

use Kinikit\Core\Exception\SerialisableException;

/**
 * Transaction Exception raised when an issue occurs usually in a call to
 * as single update function.
 */
class TransactionException extends SerialisableException {

    /**
     * @var \Netistrar\ClientAPI\Objects\Transaction\TransactionError[]
     */
    protected $transactionErrors;


    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the transactionErrors
     *
     * @return \Netistrar\ClientAPI\Objects\Transaction\TransactionError[]
     */
    public function getTransactionErrors(){
        return $this->transactionErrors;
    }


}