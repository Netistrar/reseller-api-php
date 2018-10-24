<?php

namespace Netistrar\ClientAPI\Objects\Domain;

use Kinikit\Core\Object\SerialisableObject;
/**
 * An updatable contact object used within the <a href="netistrar-domain-lifecycle-api">Netistrar Domain Lifecycle API</a> methods and as a related object to a <a href="domain-name-object">DomainNameObject</a>.
 * 
 * Changes to key data for the owner contact for GTLD domain names require verification via email to the current owner for the domain name before these changes can be applied.  If the change is accepted the
 * domain name will be locked for 60 days for transfer.
 *
  */
class DomainNameContact extends SerialisableObject {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $organisation;

    /**
     * @var string
     */
    private $street1;

    /**
     * @var string
     */
    private $street2;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $county;

    /**
     * @var string
     */
    private $postcode;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $telephoneDiallingCode;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var string
     */
    private $telephoneExt;

    /**
     * @var string
     */
    private $faxDiallingCode;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $faxExt;

    /**
     * @var mixed[string]
     */
    private $additionalData;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var DomainNameContact
     */
    protected $pendingContact;



    /**
     * Constructor
     *
    * @param  $name
    * @param  $emailAddress
    * @param  $organisation
    * @param  $street1
    * @param  $street2
    * @param  $city
    * @param  $county
    * @param  $postcode
    * @param  $country
    * @param  $telephoneDiallingCode
    * @param  $telephone
    * @param  $telephoneExt
    * @param  $faxDiallingCode
    * @param  $fax
    * @param  $faxExt
    * @param  $additionalData
    */
    public function __construct($name = null, $emailAddress = null, $organisation = null, $street1 = null, $street2 = null, $city = null, $county = null, $postcode = null, $country = null, $telephoneDiallingCode = null, $telephone = null, $telephoneExt = null, $faxDiallingCode = null, $fax = null, $faxExt = null, $additionalData = null){

        $this->name = $name;
        $this->emailAddress = $emailAddress;
        $this->organisation = $organisation;
        $this->street1 = $street1;
        $this->street2 = $street2;
        $this->city = $city;
        $this->county = $county;
        $this->postcode = $postcode;
        $this->country = $country;
        $this->telephoneDiallingCode = $telephoneDiallingCode;
        $this->telephone = $telephone;
        $this->telephoneExt = $telephoneExt;
        $this->faxDiallingCode = $faxDiallingCode;
        $this->fax = $fax;
        $this->faxExt = $faxExt;
        $this->additionalData = $additionalData;
        
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Set the name
     *
     * @param string $name
     */
    public function setName($name){
        $this->name = $name;
    }

    /**
     * Get the emailAddress
     *
     * @return string
     */
    public function getEmailAddress(){
        return $this->emailAddress;
    }

    /**
     * Set the emailAddress
     *
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress){
        $this->emailAddress = $emailAddress;
    }

    /**
     * Get the organisation
     *
     * @return string
     */
    public function getOrganisation(){
        return $this->organisation;
    }

    /**
     * Set the organisation
     *
     * @param string $organisation
     */
    public function setOrganisation($organisation){
        $this->organisation = $organisation;
    }

    /**
     * Get the street1
     *
     * @return string
     */
    public function getStreet1(){
        return $this->street1;
    }

    /**
     * Set the street1
     *
     * @param string $street1
     */
    public function setStreet1($street1){
        $this->street1 = $street1;
    }

    /**
     * Get the street2
     *
     * @return string
     */
    public function getStreet2(){
        return $this->street2;
    }

    /**
     * Set the street2
     *
     * @param string $street2
     */
    public function setStreet2($street2){
        $this->street2 = $street2;
    }

    /**
     * Get the city
     *
     * @return string
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * Set the city
     *
     * @param string $city
     */
    public function setCity($city){
        $this->city = $city;
    }

    /**
     * Get the county
     *
     * @return string
     */
    public function getCounty(){
        return $this->county;
    }

    /**
     * Set the county
     *
     * @param string $county
     */
    public function setCounty($county){
        $this->county = $county;
    }

    /**
     * Get the postcode
     *
     * @return string
     */
    public function getPostcode(){
        return $this->postcode;
    }

    /**
     * Set the postcode
     *
     * @param string $postcode
     */
    public function setPostcode($postcode){
        $this->postcode = $postcode;
    }

    /**
     * Get the country
     *
     * @return string
     */
    public function getCountry(){
        return $this->country;
    }

    /**
     * Set the country
     *
     * @param string $country
     */
    public function setCountry($country){
        $this->country = $country;
    }

    /**
     * Get the telephoneDiallingCode
     *
     * @return string
     */
    public function getTelephoneDiallingCode(){
        return $this->telephoneDiallingCode;
    }

    /**
     * Set the telephoneDiallingCode
     *
     * @param string $telephoneDiallingCode
     */
    public function setTelephoneDiallingCode($telephoneDiallingCode){
        $this->telephoneDiallingCode = $telephoneDiallingCode;
    }

    /**
     * Get the telephone
     *
     * @return string
     */
    public function getTelephone(){
        return $this->telephone;
    }

    /**
     * Set the telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone){
        $this->telephone = $telephone;
    }

    /**
     * Get the telephoneExt
     *
     * @return string
     */
    public function getTelephoneExt(){
        return $this->telephoneExt;
    }

    /**
     * Set the telephoneExt
     *
     * @param string $telephoneExt
     */
    public function setTelephoneExt($telephoneExt){
        $this->telephoneExt = $telephoneExt;
    }

    /**
     * Get the faxDiallingCode
     *
     * @return string
     */
    public function getFaxDiallingCode(){
        return $this->faxDiallingCode;
    }

    /**
     * Set the faxDiallingCode
     *
     * @param string $faxDiallingCode
     */
    public function setFaxDiallingCode($faxDiallingCode){
        $this->faxDiallingCode = $faxDiallingCode;
    }

    /**
     * Get the fax
     *
     * @return string
     */
    public function getFax(){
        return $this->fax;
    }

    /**
     * Set the fax
     *
     * @param string $fax
     */
    public function setFax($fax){
        $this->fax = $fax;
    }

    /**
     * Get the faxExt
     *
     * @return string
     */
    public function getFaxExt(){
        return $this->faxExt;
    }

    /**
     * Set the faxExt
     *
     * @param string $faxExt
     */
    public function setFaxExt($faxExt){
        $this->faxExt = $faxExt;
    }

    /**
     * Get the additionalData
     *
     * @return mixed[string]
     */
    public function getAdditionalData(){
        return $this->additionalData;
    }

    /**
     * Set the additionalData
     *
     * @param mixed[string] $additionalData
     */
    public function setAdditionalData($additionalData){
        $this->additionalData = $additionalData;
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
     * Get the pendingContact
     *
     * @return DomainNameContact
     */
    public function getPendingContact(){
        return $this->pendingContact;
    }


}