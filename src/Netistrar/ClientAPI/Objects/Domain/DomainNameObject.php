<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * A value object used within the <a href="netistrar-domain-lifecycle-api">Netistrar Domain Lifecycle API</a> methods to capture all of the properties of a domain name including related
 * Contacts and Nameservers objects.
 *
 */
class DomainNameObject extends SerialisableObject {

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    protected $ownerContact;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    protected $adminContact;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    protected $billingContact;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    protected $technicalContact;

    /**
     * @var string[]
     */
    protected $nameservers;

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
     * Get the ownerContact
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    public function getOwnerContact(){
        return $this->ownerContact;
    }

    /**
     * Get the adminContact
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    public function getAdminContact(){
        return $this->adminContact;
    }

    /**
     * Get the billingContact
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    public function getBillingContact(){
        return $this->billingContact;
    }

    /**
     * Get the technicalContact
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    public function getTechnicalContact(){
        return $this->technicalContact;
    }

    /**
     * Get the nameservers
     *
     * @return string[]
     */
    public function getNameservers(){
        return $this->nameservers;
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