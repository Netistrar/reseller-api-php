<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Encodes the results from a call to <b>getHintedAvailability</b>
 *
 */
class DomainAvailabilityResults extends SerialisableObject {

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainAvailability
     */
    protected $directResult;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[string][string]
     */
    protected $categoryResults;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[string]
     */
    protected $tldResults;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[string][]
     */
    protected $tldSuggestions;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[]
     */
    protected $suggestions;



    /**
     * Constructor
     *
    */
    public function __construct(){

        
    }

    /**
     * Get the directResult
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainAvailability
     */
    public function getDirectResult(){
        return $this->directResult;
    }

    /**
     * Get the categoryResults
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[string][string]
     */
    public function getCategoryResults(){
        return $this->categoryResults;
    }

    /**
     * Get the tldResults
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[string]
     */
    public function getTldResults(){
        return $this->tldResults;
    }

    /**
     * Get the tldSuggestions
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[string][]
     */
    public function getTldSuggestions(){
        return $this->tldSuggestions;
    }

    /**
     * Get the suggestions
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\DomainAvailability[]
     */
    public function getSuggestions(){
        return $this->suggestions;
    }


}