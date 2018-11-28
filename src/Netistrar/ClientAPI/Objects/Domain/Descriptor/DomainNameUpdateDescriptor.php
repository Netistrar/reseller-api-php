<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Descriptor for a domain name update operation.  This should be passed to the update operation on the Domains API.
 *
 */
class DomainNameUpdateDescriptor extends SerialisableObject {

    /**
     * @var string[]
     */
    private $domainNames;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    private $ownerContact;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    private $adminContact;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    private $billingContact;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact
     */
    private $technicalContact;

    /**
     * @var string[]
     */
    private $nameservers;

    /**
     * @var boolean
     */
    private $locked;

    /**
     * @var integer
     */
    private $privacyProxy;

    /**
     * @var boolean
     */
    private $autoRenew;



    /**
     * Constructor
     *
    * @param  $domainNames
    * @param  $ownerContact
    * @param  $adminContact
    * @param  $billingContact
    * @param  $technicalContact
    * @param  $nameservers
    * @param  $locked
    * @param  $privacyProxy
    * @param  $autoRenew
    */
    public function __construct($domainNames = null, $ownerContact = null, $adminContact = null, $billingContact = null, $technicalContact = null, $nameservers = null, $locked = null, $privacyProxy = null, $autoRenew = null){

        $this->domainNames = $domainNames;
        $this->ownerContact = $ownerContact;
        $this->adminContact = $adminContact;
        $this->billingContact = $billingContact;
        $this->technicalContact = $technicalContact;
        $this->nameservers = $nameservers;
        $this->locked = $locked;
        $this->privacyProxy = $privacyProxy;
        $this->autoRenew = $autoRenew;
        
    }

    /**
     * Get the domainNames
     *
     * @return string[]
     */
    public function getDomainNames(){
        return $this->domainNames;
    }

    /**
     * Set the domainNames
     *
     * @param string[] $domainNames
     */
    public function setDomainNames($domainNames){
        $this->domainNames = $domainNames;
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
     * Set the ownerContact
     *
     * @param \Netistrar\ClientAPI\Objects\Domain\DomainNameContact $ownerContact
     */
    public function setOwnerContact($ownerContact){
        $this->ownerContact = $ownerContact;
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
     * Set the adminContact
     *
     * @param \Netistrar\ClientAPI\Objects\Domain\DomainNameContact $adminContact
     */
    public function setAdminContact($adminContact){
        $this->adminContact = $adminContact;
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
     * Set the billingContact
     *
     * @param \Netistrar\ClientAPI\Objects\Domain\DomainNameContact $billingContact
     */
    public function setBillingContact($billingContact){
        $this->billingContact = $billingContact;
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
     * Set the technicalContact
     *
     * @param \Netistrar\ClientAPI\Objects\Domain\DomainNameContact $technicalContact
     */
    public function setTechnicalContact($technicalContact){
        $this->technicalContact = $technicalContact;
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
     * Set the nameservers
     *
     * @param string[] $nameservers
     */
    public function setNameservers($nameservers){
        $this->nameservers = $nameservers;
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
     * Set the locked
     *
     * @param boolean $locked
     */
    public function setLocked($locked){
        $this->locked = $locked;
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
     * Set the privacyProxy
     *
     * @param integer $privacyProxy
     */
    public function setPrivacyProxy($privacyProxy){
        $this->privacyProxy = $privacyProxy;
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
     * Set the autoRenew
     *
     * @param boolean $autoRenew
     */
    public function setAutoRenew($autoRenew){
        $this->autoRenew = $autoRenew;
    }


}