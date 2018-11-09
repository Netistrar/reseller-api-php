<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Domain availability class.
 *
 */
class DomainAvailability extends SerialisableObject {

    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var string
     */
    protected $availability;

    /**
     * @var string
     */
    protected $pricingType;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainAvailabilityPrice[string][integer]
     */
    protected $prices;

    /**
     * @var mixed[string]
     */
    protected $additionalData;



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
     * Get the availability
     *
     * @return string
     */
    public function getAvailability(){
        return $this->availability;
    }

    /**
     * Get the pricingType
     *
     * @return string
     */
    public function getPricingType(){
        return $this->pricingType;
    }

    /**
     * Get the prices
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainAvailabilityPrice[string][integer]
     */
    public function getPrices(){
        return $this->prices;
    }

    /**
     * Get the additionalData
     *
     * @return mixed[string]
     */
    public function getAdditionalData(){
        return $this->additionalData;
    }


}