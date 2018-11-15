<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Descriptor for a domain name hinted availability operation.  This is passed to the getHintedAvailability function on the Domains API.
 *
 */
class DomainNameAvailabilityDescriptor extends SerialisableObject {

    /**
     * @var string
     */
    private $searchString;

    /**
     * @var string[]
     */
    private $tldCategories;

    /**
     * @var string[]
     */
    private $tlds;

    /**
     * @var boolean
     */
    private $suggestions;

    /**
     * @var \Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameSuggestionOptions
     */
    private $suggestionOptions;



    /**
     * Constructor
     *
    * @param  $searchString
    * @param  $tldCategories
    * @param  $tlds
    * @param  $suggestions
    * @param  $suggestionOptions
    */
    public function __construct($searchString = null, $tldCategories = null, $tlds = null, $suggestions = null, $suggestionOptions = null){

        $this->searchString = $searchString;
        $this->tldCategories = $tldCategories;
        $this->tlds = $tlds;
        $this->suggestions = $suggestions;
        $this->suggestionOptions = $suggestionOptions;
        
    }

    /**
     * Get the searchString
     *
     * @return string
     */
    public function getSearchString(){
        return $this->searchString;
    }

    /**
     * Set the searchString
     *
     * @param string $searchString
     */
    public function setSearchString($searchString){
        $this->searchString = $searchString;
    }

    /**
     * Get the tldCategories
     *
     * @return string[]
     */
    public function getTldCategories(){
        return $this->tldCategories;
    }

    /**
     * Set the tldCategories
     *
     * @param string[] $tldCategories
     */
    public function setTldCategories($tldCategories){
        $this->tldCategories = $tldCategories;
    }

    /**
     * Get the tlds
     *
     * @return string[]
     */
    public function getTlds(){
        return $this->tlds;
    }

    /**
     * Set the tlds
     *
     * @param string[] $tlds
     */
    public function setTlds($tlds){
        $this->tlds = $tlds;
    }

    /**
     * Get the suggestions
     *
     * @return boolean
     */
    public function getSuggestions(){
        return $this->suggestions;
    }

    /**
     * Set the suggestions
     *
     * @param boolean $suggestions
     */
    public function setSuggestions($suggestions){
        $this->suggestions = $suggestions;
    }

    /**
     * Get the suggestionOptions
     *
     * @return \Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameSuggestionOptions
     */
    public function getSuggestionOptions(){
        return $this->suggestionOptions;
    }

    /**
     * Set the suggestionOptions
     *
     * @param \Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameSuggestionOptions $suggestionOptions
     */
    public function setSuggestionOptions($suggestionOptions){
        $this->suggestionOptions = $suggestionOptions;
    }


}