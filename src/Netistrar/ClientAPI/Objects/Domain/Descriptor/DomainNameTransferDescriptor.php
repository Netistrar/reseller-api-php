<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Descriptor for a domain name create operation.  This should be passed to validate and create transfer operations on the Domains API.
 *
 */
class DomainNameTransferDescriptor extends SerialisableObject {

    /**
     * @var string[]
     */
    private $transferIdentifiers;

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
    * @param  $transferIdentifiers
    * @param  $ownerContact
    * @param  $adminContact
    * @param  $billingContact
    * @param  $technicalContact
    * @param  $privacyProxy
    * @param  $autoRenew
    */
    public function __construct($transferIdentifiers = null, $ownerContact = null, $adminContact = null, $billingContact = null, $technicalContact = null, $privacyProxy = 1, $autoRenew = null){

        $this->transferIdentifiers = $transferIdentifiers;
        $this->ownerContact = $ownerContact;
        $this->adminContact = $adminContact;
        $this->billingContact = $billingContact;
        $this->technicalContact = $technicalContact;
        $this->privacyProxy = $privacyProxy;
        $this->autoRenew = $autoRenew;
        
    }

    /**
     * Get the transferIdentifiers
     *
     * @return string[]
     */
    public function getTransferIdentifiers(){
        return $this->transferIdentifiers;
    }

    /**
     * Set the transferIdentifiers
     *
     * @param string[] $transferIdentifiers
     */
    public function setTransferIdentifiers($transferIdentifiers){
        $this->transferIdentifiers = $transferIdentifiers;
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