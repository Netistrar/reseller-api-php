<?php

namespace Netistrar\ClientAPI\Objects\Domain\Descriptor;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Configuration options for domain name suggestions.
 *
 * Class DomainNameSuggestionOptions
 */
class DomainNameSuggestionOptions extends SerialisableObject {

    /**
     * @var boolean
     */
    private $includeTlds;

    /**
     * @var boolean
     */
    private $includeCategories;

    /**
     * @var integer
     */
    private $fillCount;



    /**
     * Constructor
     *
    * @param  $includeTlds
    * @param  $includeCategories
    * @param  $fillCount
    */
    public function __construct($includeTlds = 1, $includeCategories = 1, $fillCount = 25){

        $this->includeTlds = $includeTlds;
        $this->includeCategories = $includeCategories;
        $this->fillCount = $fillCount;
        
    }

    /**
     * Get the includeTlds
     *
     * @return boolean
     */
    public function getIncludeTlds(){
        return $this->includeTlds;
    }

    /**
     * Set the includeTlds
     *
     * @param boolean $includeTlds
     */
    public function setIncludeTlds($includeTlds){
        $this->includeTlds = $includeTlds;
    }

    /**
     * Get the includeCategories
     *
     * @return boolean
     */
    public function getIncludeCategories(){
        return $this->includeCategories;
    }

    /**
     * Set the includeCategories
     *
     * @param boolean $includeCategories
     */
    public function setIncludeCategories($includeCategories){
        $this->includeCategories = $includeCategories;
    }

    /**
     * Get the fillCount
     *
     * @return integer
     */
    public function getFillCount(){
        return $this->fillCount;
    }

    /**
     * Set the fillCount
     *
     * @param integer $fillCount
     */
    public function setFillCount($fillCount){
        $this->fillCount = $fillCount;
    }


}