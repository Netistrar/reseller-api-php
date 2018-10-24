<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Descriptor for a domain name renew operation.  This should be passed to the renew operation on the Domains API.
 *
 */
class DomainNameRenewDescriptor extends SerialisableObject {

    /**
     * @var string[]
     */
    private $domainNames;

    /**
     * @var integer
     */
    private $additionalYears;



    /**
     * Constructor
     *
    * @param  $domainNames
    * @param  $additionalYears
    */
    public function __construct($domainNames = null, $additionalYears = null){

        $this->domainNames = $domainNames;
        $this->additionalYears = $additionalYears;
        
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
     */
    public function setAdditionalYears($additionalYears){
        $this->additionalYears = $additionalYears;
    }


}