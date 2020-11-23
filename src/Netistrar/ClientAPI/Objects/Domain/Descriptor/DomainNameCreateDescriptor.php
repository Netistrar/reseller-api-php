<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Descriptor for a domain name create operation.  This should be passed to validate and create operations on the Domains API.
*/
class DomainNameCreateDescriptor extends SerialisableObject {

    /**
     *
     * @var string[] the array of domain names to be created.
     */
    private $domainNames;

    /**
     *
     * @var integer The number of years for which these domain names will be created.
     */
    private $registrationYears;

    /**
     *
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainNameContact The details for the owner contact (sometimes called the Registrant)
     */
    private $ownerContact;

    /**
     *
     * @var string[] An array of nameserver string to set for this domain name on creation.
     */
    private $nameservers;

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
     * @var bool[string] 
     */
    private $disclosedFields;

    /**
     *
     * @var boolean A boolean indicator as to whether the an attempt will be made to auto renew this domain using account payment methods (defaults to 0)
     */
    private $autoRenew;

    /**
     *
     * @var string[] An array of string tags to assign to this domain for organisational purposes.
     */
    private $tags;

    /**
     *
     * @var string[string] An associative array of registration codes indexed by domain name which must be supplied as a confirmation check for any domain names which are premium names.  These codes are obtained via a call to the <b>getLiveAvailability</b> function on the <a href="api:domains-api">Domain API</a> where it will be contained in the <b>premiumRegistrationCode</b> sub property of the <b>additionalData</b> member on the returned <a href="object:Netistrar/WebServices/Common/Objects/Domain/DomainAvailability">DomainAvailability</a> object.
     */
    private $premiumRegistrationCodes;



    /**
     * Constructor
     *
     * @param  $domainNames
     * @param  $registrationYears
     * @param  $ownerContact
     * @param  $nameservers
     * @param  $adminContact
     * @param  $billingContact
     * @param  $technicalContact
     * @param  $privacyProxy
     * @param  $disclosedFields
     * @param  $autoRenew
     * @param  $tags
     * @param  $premiumRegistrationCodes
     */
    public function __construct($domainNames = null, $registrationYears = null, $ownerContact = null, $nameservers = null, $adminContact = null, $billingContact = null, $technicalContact = null, $privacyProxy = 1, $disclosedFields = null, $autoRenew = null, $tags = null, $premiumRegistrationCodes = null){

        $this->domainNames = $domainNames;
        $this->registrationYears = $registrationYears;
        $this->ownerContact = $ownerContact;
        $this->nameservers = $nameservers;
        $this->adminContact = $adminContact;
        $this->billingContact = $billingContact;
        $this->technicalContact = $technicalContact;
        $this->privacyProxy = $privacyProxy;
        $this->disclosedFields = $disclosedFields;
        $this->autoRenew = $autoRenew;
        $this->tags = $tags;
        $this->premiumRegistrationCodes = $premiumRegistrationCodes;
        
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
     * @return DomainNameCreateDescriptor
     */
    public function setDomainNames($domainNames){
        $this->domainNames = $domainNames;
        return $this;
    }

    /**
     * Get the registrationYears
     *
     * @return integer
     */
    public function getRegistrationYears(){
        return $this->registrationYears;
    }

    /**
     * Set the registrationYears
     *
     * @param integer $registrationYears
     * @return DomainNameCreateDescriptor
     */
    public function setRegistrationYears($registrationYears){
        $this->registrationYears = $registrationYears;
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
     * @return DomainNameCreateDescriptor
     */
    public function setOwnerContact($ownerContact){
        $this->ownerContact = $ownerContact;
        return $this;
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
     * @return DomainNameCreateDescriptor
     */
    public function setNameservers($nameservers){
        $this->nameservers = $nameservers;
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
     * @return DomainNameCreateDescriptor
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
     * @return DomainNameCreateDescriptor
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
     * @return DomainNameCreateDescriptor
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
     * @return DomainNameCreateDescriptor
     */
    public function setPrivacyProxy($privacyProxy){
        $this->privacyProxy = $privacyProxy;
        return $this;
    }

    /**
     * Get the disclosedFields
     *
     * @return bool[string]
     */
    public function getDisclosedFields(){
        return $this->disclosedFields;
    }

    /**
     * Set the disclosedFields
     *
     * @param bool[string] $disclosedFields
     * @return DomainNameCreateDescriptor
     */
    public function setDisclosedFields($disclosedFields){
        $this->disclosedFields = $disclosedFields;
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
     * @return DomainNameCreateDescriptor
     */
    public function setAutoRenew($autoRenew){
        $this->autoRenew = $autoRenew;
        return $this;
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
     * Set the tags
     *
     * @param string[] $tags
     * @return DomainNameCreateDescriptor
     */
    public function setTags($tags){
        $this->tags = $tags;
        return $this;
    }

    /**
     * Get the premiumRegistrationCodes
     *
     * @return string[string]
     */
    public function getPremiumRegistrationCodes(){
        return $this->premiumRegistrationCodes;
    }

    /**
     * Set the premiumRegistrationCodes
     *
     * @param string[string] $premiumRegistrationCodes
     * @return DomainNameCreateDescriptor
     */
    public function setPremiumRegistrationCodes($premiumRegistrationCodes){
        $this->premiumRegistrationCodes = $premiumRegistrationCodes;
        return $this;
    }


}