<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * This encodes details about a domain transfer.  When a domain name is in the <i>TRANSFER_IN_AWAITING_RESPONSE</i> status this will be fully populated with details about the
 * transfer window as obtained from the Registry.
 * <br /><br />
 * Otherwise if the domain name is in a pending confirmation status this will only have the <a href="#domainName">domainName</a> and <a href="#status">status</a> members set.
 *
 */
class DomainNameTransferStatus extends SerialisableObject {

    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $transferStatus;

    /**
     * @var string
     */
    protected $transferStartedDate;

    /**
     * @var string
     */
    protected $transferExpiryDate;

    /**
     * @var string
     */
    protected $domainExpiryDate;



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
     * Get the status
     *
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * Get the transferStatus
     *
     * @return string
     */
    public function getTransferStatus(){
        return $this->transferStatus;
    }

    /**
     * Get the transferStartedDate
     *
     * @return string
     */
    public function getTransferStartedDate(){
        return $this->transferStartedDate;
    }

    /**
     * Get the transferExpiryDate
     *
     * @return string
     */
    public function getTransferExpiryDate(){
        return $this->transferExpiryDate;
    }

    /**
     * Get the domainExpiryDate
     *
     * @return string
     */
    public function getDomainExpiryDate(){
        return $this->domainExpiryDate;
    }


}