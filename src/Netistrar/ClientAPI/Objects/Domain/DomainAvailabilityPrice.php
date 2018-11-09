<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Domain Availability Price objects are returned as part of the <a href="domain-availability-object">DomainAvailability</a> object structure.  They encode the price for an operation (registration, renewal, transfer) for a domain name
 * for a number of years.  The pricing fields returned will vary depending upon the price type (standard  / premium) and whether or not the pricing is a hint derived from the Netistrar pricing cache or confirmed pricing derived from a
 * call to the getLiveDomainAvailability within the <a href="netistrar-domain-availability-api">Netistrar Domain Availability API</a>.
 *
 */
class DomainAvailabilityPrice extends SerialisableObject {

    /**
     * @var string
     */
    protected $operation;

    /**
     * @var integer
     */
    protected $numberOfYears;

    /**
     * @var string
     */
    protected $priceType;

    /**
     * @var float
     */
    protected $standardBuyPrice;

    /**
     * @var float
     */
    protected $hintedBuyPrice;

    /**
     * @var float
     */
    protected $confirmedBuyPrice;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the operation
     *
     * @return string
     */
    public function getOperation(){
        return $this->operation;
    }

    /**
     * Get the numberOfYears
     *
     * @return integer
     */
    public function getNumberOfYears(){
        return $this->numberOfYears;
    }

    /**
     * Get the priceType
     *
     * @return string
     */
    public function getPriceType(){
        return $this->priceType;
    }

    /**
     * Get the standardBuyPrice
     *
     * @return float
     */
    public function getStandardBuyPrice(){
        return $this->standardBuyPrice;
    }

    /**
     * Get the hintedBuyPrice
     *
     * @return float
     */
    public function getHintedBuyPrice(){
        return $this->hintedBuyPrice;
    }

    /**
     * Get the confirmedBuyPrice
     *
     * @return float
     */
    public function getConfirmedBuyPrice(){
        return $this->confirmedBuyPrice;
    }


}