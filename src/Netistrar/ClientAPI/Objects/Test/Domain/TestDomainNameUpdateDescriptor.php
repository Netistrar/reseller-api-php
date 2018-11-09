<?php

namespace Netistrar\ClientAPI\Objects\Test\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Update descriptor for updating one or more domain names with attributes not normally updatable for testing
 * purposes.
 *
 */
class TestDomainNameUpdateDescriptor extends SerialisableObject {

    /**
     * @var string[]
     */
    private $domainNames;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $registeredDate;

    /**
     * @var string
     */
    private $lockedUntil;

    /**
     * @var boolean
     */
    private $locked;



    /**
     * Constructor
     *
    * @param  $domainNames
    * @param  $status
    * @param  $registeredDate
    * @param  $lockedUntil
    * @param  $locked
    */
    public function __construct($domainNames = null, $status = null, $registeredDate = null, $lockedUntil = null, $locked = null){

        $this->domainNames = $domainNames;
        $this->status = $status;
        $this->registeredDate = $registeredDate;
        $this->lockedUntil = $lockedUntil;
        $this->locked = $locked;
        
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
     * Get the status
     *
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * Set the status
     *
     * @param string $status
     */
    public function setStatus($status){
        $this->status = $status;
    }

    /**
     * Get the registeredDate
     *
     * @return string
     */
    public function getRegisteredDate(){
        return $this->registeredDate;
    }

    /**
     * Set the registeredDate
     *
     * @param string $registeredDate
     */
    public function setRegisteredDate($registeredDate){
        $this->registeredDate = $registeredDate;
    }

    /**
     * Get the lockedUntil
     *
     * @return string
     */
    public function getLockedUntil(){
        return $this->lockedUntil;
    }

    /**
     * Set the lockedUntil
     *
     * @param string $lockedUntil
     */
    public function setLockedUntil($lockedUntil){
        $this->lockedUntil = $lockedUntil;
    }

    /**
     * Get the locked
     *
     * @return boolean
     */
    public function getLocked(){
        return $this->locked;
    }

    /**
     * Set the locked
     *
     * @param boolean $locked
     */
    public function setLocked($locked){
        $this->locked = $locked;
    }


}