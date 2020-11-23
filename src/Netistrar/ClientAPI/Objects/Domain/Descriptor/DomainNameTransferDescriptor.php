<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Descriptor for a domain name create operation.  This should be passed to validate and create transfer operations on the Domains API.
*/
class DomainNameTransferDescriptor extends SerialisableObject {

    /**
     * An array of identifier entries for transfer in.
     * In the case of GTLDs the entry should be in the format <b>domain name,auth code</b>
     * For .UK and other non-auth code TLDs this should simply be the domain name.
     *
     * @var string[] 
     */
    private $transferIdentifiers;

    /**
     *
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact The details for the owner contact (sometimes called the Registrant)
     */
    private $ownerContact;

    /**
     *
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact The details for the admin contact for these domains (required for some TLDs).  If this is required but not supplied it will use the Default Admin contact details specified in the Netistrar control panel under My Account -> API settings or will generate a Validation Error if these are not defined.
     */
    private $adminContact;

    /**
     *
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact The details for the billing contact for these domains (required for some TLDs).  If this is required but not supplied it will use the Default Billing contact details specified in the Netistrar control panel under My Account -> API settings or will generate a Validation Error if these are not defined.
     */
    private $billingContact;

    /**
     *
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact The details for the technical contact for these domains (required for some TLDs).  If this is required but not supplied it will use the Default Billing contact details specified in the Netistrar control panel under My Account -> API settings or will generate a Validation Error if these are not defined.
     */
    private $technicalContact;

    /**
     *
     * @var integer This should be set to one of the following values: <br><br><b>0</b> If limited details are to be published via the WHOIS system for all supplied domains according to Registry policy.<br><b>1</b> if the free Netistrar Privacy Proxy service will be used for all supplied domains.
     */
    private $privacyProxy;

    /**
     *
     * @var boolean A boolean indicator as to whether the an attempt will be made to auto renew this domain using account payment methods (defaults to 0)
     */
    private $autoRenew;

    /**
     *
     * @var string[string] An associative array of transfer codes indexed by domain name which must be supplied as a confirmation check for any domain names which are premium names.  These codes are obtained via a call to the <b>getLiveAvailability</b> function on the <a href="api:domains-api">Domain API</a> where it will be contained in the <b>premiumTransferCode</b> sub property of the <b>additionalData</b> member on the returned <a href="object:Netistrar/WebServices/Common/Objects/Domain/DomainAvailability">DomainAvailability</a> object.
     */
    private $premiumTransferCodes;



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
     * @param  $premiumTransferCodes
     */
    public function __construct($transferIdentifiers = null, $ownerContact = null, $adminContact = null, $billingContact = null, $technicalContact = null, $privacyProxy = 1, $autoRenew = null, $premiumTransferCodes = null){

        $this->transferIdentifiers = $transferIdentifiers;
        $this->ownerContact = $ownerContact;
        $this->adminContact = $adminContact;
        $this->billingContact = $billingContact;
        $this->technicalContact = $technicalContact;
        $this->privacyProxy = $privacyProxy;
        $this->autoRenew = $autoRenew;
        $this->premiumTransferCodes = $premiumTransferCodes;
        
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
     * @return DomainNameTransferDescriptor
     */
    public function setTransferIdentifiers($transferIdentifiers){
        $this->transferIdentifiers = $transferIdentifiers;
        return $this;
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
     * @return DomainNameTransferDescriptor
     */
    public function setOwnerContact($ownerContact){
        $this->ownerContact = $ownerContact;
        return $this;
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
     * @return DomainNameTransferDescriptor
     */
    public function setAdminContact($adminContact){
        $this->adminContact = $adminContact;
        return $this;
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
     * @return DomainNameTransferDescriptor
     */
    public function setBillingContact($billingContact){
        $this->billingContact = $billingContact;
        return $this;
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
     * @return DomainNameTransferDescriptor
     */
    public function setTechnicalContact($technicalContact){
        $this->technicalContact = $technicalContact;
        return $this;
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
     * @return DomainNameTransferDescriptor
     */
    public function setPrivacyProxy($privacyProxy){
        $this->privacyProxy = $privacyProxy;
        return $this;
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
     * @return DomainNameTransferDescriptor
     */
    public function setAutoRenew($autoRenew){
        $this->autoRenew = $autoRenew;
        return $this;
    }

    /**
     * Get the premiumTransferCodes
     *
     * @return string[string]
     */
    public function getPremiumTransferCodes(){
        return $this->premiumTransferCodes;
    }

    /**
     * Set the premiumTransferCodes
     *
     * @param string[string] $premiumTransferCodes
     * @return DomainNameTransferDescriptor
     */
    public function setPremiumTransferCodes($premiumTransferCodes){
        $this->premiumTransferCodes = $premiumTransferCodes;
        return $this;
    }


}