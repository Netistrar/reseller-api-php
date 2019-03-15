<?php

namespace Netistrar\ClientAPI\Objects\GSuite;

use Kinikit\Core\Object\SerialisableObject;
/**
 * A read only object representing a GSuite subscription for an account holder.
*/
class GSuite extends SerialisableObject {

    /**
     * If this GSuite has been activated the identifier will be the same as the primary domain name.  Otherwise it will be a numeric
     * identifier used temporarily until the domain name has been assigned.
     *
     * @var string 
     */
    protected $identifier;

    /**
     * The GSuite plan type - either <b>Monthly</b> or <b>Annual</b>.
     *
     * @var string 
     */
    protected $planType;

    /**
     * The number of licenced users at the point of renewal.  This will be different to numberOfLicencedUsers
     * if there has been a request to reduce licences.
     *
     * @var integer 
     */
    protected $renewingNumberOfUsers;

    /**
     * The primary domain name for this GSuite if defined
     *
     * @var string 
     */
    protected $primaryDomainName;

    /**
     * The GSuite product - either <b>Basic</b>, <b>Business</b> or <b>Enterprise</b>.
     *
     * @var string 
     */
    protected $gsuiteProduct;

    /**
     * The date that this subscription is next due to renew.
     *
     * @var string 
     */
    protected $renewalDate;

    /**
     * Number of licenced users currently in play.
     *
     * @var integer 
     */
    protected $numberOfLicencedUsers;

    /**
     * The plan type being activated at renewal time (either <b>Monthly</b> or <b>Annual</b>)
     * This will match the plan type unless another one has been scheduled.
     *
     * @var string 
     */
    protected $renewingPlanType;

    /**
     * The status of this GSuite subscription
     * should be either <b>PENDING_DOMAIN</b>, <b>PENDING_ACTIVATION</b>, <b>ACTIVE</b> or <b>SUSPENDED</b>.
     *
     * @var string 
     */
    protected $status;

    /**
     * The account contact as a nested object
     *
     * @var Netistrar\ClientAPI\Objects\Contact\Contact 
     */
    protected $contact;

    /**
     * The google verification token used for DNS verification.
     *
     * @var string 
     */
    protected $verificationToken;

    /**
     * Flag determining whether this subscription will auto renew.  In the case of an <b>Annual</b>
     * subscription this determines whether or not the user needs to renew it manually.
     * In the case of a <b>Monthly</b> subscription if this is set to false the subscription will be cancelled
     * at the end of the current monthly period.
     *
     * @var boolean 
     */
    protected $autoRenew;



    /**
     * Constructor
     *
     */
    public function __construct(){

        
    }

    /**
     * Get the identifier
     *
     * @return string
     */
    public function getIdentifier(){
        return $this->identifier;
    }

    /**
     * Get the planType
     *
     * @return string
     */
    public function getPlanType(){
        return $this->planType;
    }

    /**
     * Get the renewingNumberOfUsers
     *
     * @return integer
     */
    public function getRenewingNumberOfUsers(){
        return $this->renewingNumberOfUsers;
    }

    /**
     * Get the primaryDomainName
     *
     * @return string
     */
    public function getPrimaryDomainName(){
        return $this->primaryDomainName;
    }

    /**
     * Get the gsuiteProduct
     *
     * @return string
     */
    public function getGsuiteProduct(){
        return $this->gsuiteProduct;
    }

    /**
     * Get the renewalDate
     *
     * @return string
     */
    public function getRenewalDate(){
        return $this->renewalDate;
    }

    /**
     * Get the numberOfLicencedUsers
     *
     * @return integer
     */
    public function getNumberOfLicencedUsers(){
        return $this->numberOfLicencedUsers;
    }

    /**
     * Get the renewingPlanType
     *
     * @return string
     */
    public function getRenewingPlanType(){
        return $this->renewingPlanType;
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
     * Get the contact
     *
     * @return Netistrar\ClientAPI\Objects\Contact\Contact
     */
    public function getContact(){
        return $this->contact;
    }

    /**
     * Get the verificationToken
     *
     * @return string
     */
    public function getVerificationToken(){
        return $this->verificationToken;
    }

    /**
     * Get the autoRenew
     *
     * @return boolean
     */
    public function getAutoRenew(){
        return $this->autoRenew;
    }


}