<?php

namespace Netistrar\ClientAPI\Objects\Contact;

use Kinikit\Core\Object\SerialisableObject;
/**
 * Base contact class used for general API use.
 * Class Contact
*/
class Contact extends SerialisableObject {

    /**
     * The full name of this contact
     *
     * @var string 
     */
    private $name;

    /**
     * The email address for this contact.
     *
     * @var string 
     */
    private $emailAddress;

    /**
     * The organisation if applicable for this contact.
     *
     * @var string 
     */
    private $organisation;

    /**
     * The first line of the street address for this contact
     *
     * @var string 
     */
    private $street1;

    /**
     * The second line of the street address for this contact
     *
     * @var string 
     */
    private $street2;

    /**
     * The city of the street address for this contact
     *
     * @var string 
     */
    private $city;

    /**
     * The county of the street address for this contact
     *
     * @var string 
     */
    private $county;

    /**
     * The postcode for this contact
     *
     * @var string 
     */
    private $postcode;

    /**
     * The 2 digit country code for this contact (e.g. GB).  If not supplied, this will default to <b>GB</b>
     *
     * @var string 
     */
    private $country;

    /**
     * The telephone international dialling code with leading + if a telephone number is being supplied (e.g. +44, +1 etc)
     *
     * @var string 
     */
    private $telephoneDiallingCode;

    /**
     * The local telephone number bit after the dialling code with no spaces (e.g. 1235987878)
     *
     * @var string 
     */
    private $telephone;

    /**
     * An optional telephone extension (e.g. 201)
     *
     * @var string 
     */
    private $telephoneExt;

    /**
     * The fax international dialling code with leading + if a fax number is being supplied (e.g. +44, +1 etc)
     *
     * @var string 
     */
    private $faxDiallingCode;

    /**
     * The local number bit after the dialling code with no spaces (e.g. 1235987878)
     *
     * @var string 
     */
    private $fax;

    /**
     * An optional fax extension (e.g. 201)
     *
     * @var string 
     */
    private $faxExt;



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
     */
    public function __construct($name = null, $emailAddress = null, $organisation = null, $street1 = null, $street2 = null, $city = null, $county = null, $postcode = null, $country = null, $telephoneDiallingCode = null, $telephone = null, $telephoneExt = null, $faxDiallingCode = null, $fax = null, $faxExt = null){

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
     * @return Contact
     */
    public function setName($name){
        $this->name = $name;
        return $this;
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
     * @return Contact
     */
    public function setEmailAddress($emailAddress){
        $this->emailAddress = $emailAddress;
        return $this;
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
     * @return Contact
     */
    public function setOrganisation($organisation){
        $this->organisation = $organisation;
        return $this;
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
     * @return Contact
     */
    public function setStreet1($street1){
        $this->street1 = $street1;
        return $this;
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
     * @return Contact
     */
    public function setStreet2($street2){
        $this->street2 = $street2;
        return $this;
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
     * @return Contact
     */
    public function setCity($city){
        $this->city = $city;
        return $this;
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
     * @return Contact
     */
    public function setCounty($county){
        $this->county = $county;
        return $this;
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
     * @return Contact
     */
    public function setPostcode($postcode){
        $this->postcode = $postcode;
        return $this;
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
     * @return Contact
     */
    public function setCountry($country){
        $this->country = $country;
        return $this;
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
     * @return Contact
     */
    public function setTelephoneDiallingCode($telephoneDiallingCode){
        $this->telephoneDiallingCode = $telephoneDiallingCode;
        return $this;
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
     * @return Contact
     */
    public function setTelephone($telephone){
        $this->telephone = $telephone;
        return $this;
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
     * @return Contact
     */
    public function setTelephoneExt($telephoneExt){
        $this->telephoneExt = $telephoneExt;
        return $this;
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
     * @return Contact
     */
    public function setFaxDiallingCode($faxDiallingCode){
        $this->faxDiallingCode = $faxDiallingCode;
        return $this;
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
     * @return Contact
     */
    public function setFax($fax){
        $this->fax = $fax;
        return $this;
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
     * @return Contact
     */
    public function setFaxExt($faxExt){
        $this->faxExt = $faxExt;
        return $this;
    }


}