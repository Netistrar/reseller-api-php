<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 *
 * Domain name summary object
 */
class DomainNameSummary extends SerialisableObject {

    /**
     * @var string
     */
    protected $ownerName;

    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var string
     */
    protected $registeredDate;

    /**
     * @var string
     */
    protected $expiryDate;

    /**
     * @var boolean
     */
    protected $locked;

    /**
     * @var string
     */
    protected $lockedUntil;

    /**
     * @var string
     */
    protected $authCode;

    /**
     * @var boolean
     */
    protected $autoRenew;

    /**
     * @var integer
     */
    protected $privacyProxy;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string[]
     */
    protected $tags;

    /**
     * @var string
     */
    protected $dnsProvider;

    /**
     * @var string
     */
    protected $emailProvider;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the ownerName
     *
     * @return string
     */
    public function getOwnerName(){
        return $this->ownerName;
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
     * Get the registeredDate
     *
     * @return string
     */
    public function getRegisteredDate(){
        return $this->registeredDate;
    }

    /**
     * Get the expiryDate
     *
     * @return string
     */
    public function getExpiryDate(){
        return $this->expiryDate;
    }

    /**
     * Get the locked
     *
     * @return boolean
     */
    public function getLocked(){
        return $this->locked;
    }

    /**
     * Get the lockedUntil
     *
     * @return string
     */
    public function getLockedUntil(){
        return $this->lockedUntil;
    }

    /**
     * Get the authCode
     *
     * @return string
     */
    public function getAuthCode(){
        return $this->authCode;
    }

    /**
     * Get the autoRenew
     *
     * @return boolean
     */
    public function getAutoRenew(){
        return $this->autoRenew;
    }

    /**
     * Get the privacyProxy
     *
     * @return integer
     */
    public function getPrivacyProxy(){
        return $this->privacyProxy;
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
     * Get the tags
     *
     * @return string[]
     */
    public function getTags(){
        return $this->tags;
    }

    /**
     * Get the dnsProvider
     *
     * @return string
     */
    public function getDnsProvider(){
        return $this->dnsProvider;
    }

    /**
     * Get the emailProvider
     *
     * @return string
     */
    public function getEmailProvider(){
        return $this->emailProvider;
    }


}