<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Descriptor for a domain name renew operation.  This should be passed to the renew operation on the Domains API.
*/
class DomainNameRenewDescriptor extends SerialisableObject {

    /**
     *
     * @var string[] the array of domain names to be renewed.
     */
    private $domainNames;

    /**
     *
     * @var integer The number of years to be added to the supplied domain names
     */
    private $additionalYears;

    /**
     *
     * @var string[string] An associative array of renewal codes indexed by domain name which must be supplied as a confirmation check for any domain names which are premium names.  These codes are obtained via a call to the <b>getLiveAvailability</b> function on the <a href="api:domains-api">Domain API</a> where it will be contained in the <b>premiumRenewalCode</b> sub property of the <b>additionalData</b> member on the returned <a href="object:Netistrar/WebServices/Common/Objects/Domain/DomainAvailability">DomainAvailability</a> object.
     */
    private $premiumRenewalCodes;



    /**
     * Constructor
     *
     * @param  $domainNames
     * @param  $additionalYears
     * @param  $premiumRenewalCodes
     */
    public function __construct($domainNames = null, $additionalYears = null, $premiumRenewalCodes = null){

        $this->domainNames = $domainNames;
        $this->additionalYears = $additionalYears;
        $this->premiumRenewalCodes = $premiumRenewalCodes;
        
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
     * @return DomainNameRenewDescriptor
     */
    public function setDomainNames($domainNames){
        $this->domainNames = $domainNames;
        return $this;
    }

    /**
     * Get the additionalYears
     *
     * @return integer
     */
    public function getAdditionalYears(){
        return $this->additionalYears;
    }

    /**
     * Set the additionalYears
     *
     * @param integer $additionalYears
     * @return DomainNameRenewDescriptor
     */
    public function setAdditionalYears($additionalYears){
        $this->additionalYears = $additionalYears;
        return $this;
    }

    /**
     * Get the premiumRenewalCodes
     *
     * @return string[string]
     */
    public function getPremiumRenewalCodes(){
        return $this->premiumRenewalCodes;
    }

    /**
     * Set the premiumRenewalCodes
     *
     * @param string[string] $premiumRenewalCodes
     * @return DomainNameRenewDescriptor
     */
    public function setPremiumRenewalCodes($premiumRenewalCodes){
        $this->premiumRenewalCodes = $premiumRenewalCodes;
        return $this;
    }


}