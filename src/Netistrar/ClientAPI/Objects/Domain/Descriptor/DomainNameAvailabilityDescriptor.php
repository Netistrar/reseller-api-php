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
     * Constructor
     *
    * @param  $searchString
    * @param  $tldCategories
    * @param  $tlds
    */
    public function __construct($searchString = null, $tldCategories = null, $tlds = null){

        $this->searchString = $searchString;
        $this->tldCategories = $tldCategories;
        $this->tlds = $tlds;
        
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


}