<?php

namespace Netistrar\ClientAPI\Controllers;

use Kinikit\Core\Util\HTTP\WebServiceProxy;

/**
 * The GSuite API provides functionality to create and manage GSuite Plans
*/
class gsuite extends WebServiceProxy {

    /**
     * List all G Suite subscriptions within your account.
     *
     * @param integer $pageSize
     * @param integer $page
     * @param integer $orderBy
     * @return \Netistrar\ClientAPI\Objects\GSuite\GSuite[]
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function list($pageSize = 10, $page = 1, $orderBy = domainName){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        return parent::callMethod("", "GET", array("pageSize" => $pageSize, "page" => $page, "orderBy" => $orderBy),null,"\Netistrar\ClientAPI\Objects\GSuite\GSuite[]",$expectedExceptions);
    }

    /**
     * Get a single G Suite subscription within your account by domain name.
     *
     * @param string $domainName
     * @return \Netistrar\ClientAPI\Objects\GSuite\GSuite
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function get($domainName){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        return parent::callMethod("$domainName", "GET", array(),null,"\Netistrar\ClientAPI\Objects\GSuite\GSuite",$expectedExceptions);
    }

    /**
     * Check whether the supplied domain name is available for creating a G Suite subscription.
     * This will return one of the following values
     * <br><b>AVAILABLE</b> if the domain is available for attachment of a new G Suite subscription
     * <b>UNAVAILABLE</b> if another G Suite subscription is attached to this domain already externally.
     * <b>ALREADY_ATTACHED</b> if another G Suite subscription is attached to this domain from within your account.
     *
     * @param string $domainName
     * @return string
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function available($domainName){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        return parent::callMethod("available/$domainName", "GET", array(),null,"string",$expectedExceptions);
    }

    /**
     * Create a G Suite subscription for an existing domain within your account.
     *
     * @param string $domainName
     * @param Netistrar\ClientAPI\Objects\GSuite\Descriptor\GSuiteCreateDescriptor $createDescriptor
     * @return \Netistrar\ClientAPI\Objects\Transaction\Transaction
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function create($domainName, $createDescriptor){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        return parent::callMethod("$domainName", "POST", array(),$createDescriptor,"\Netistrar\ClientAPI\Objects\Transaction\Transaction",$expectedExceptions);
    }

    /**
     * Update a G Suite subscription for an existing subscription within your account.
     *
     * @param string $domainName
     * @param Netistrar\ClientAPI\Objects\GSuite\Descriptor\GSuiteUpdateDescriptor $updateDescriptor
     * @return Netistrar\ClientAPI\Objects\Transaction\Transaction
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function update($domainName, $updateDescriptor){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        return parent::callMethod("$domainName", "PUT", array(),$updateDescriptor,"Netistrar\ClientAPI\Objects\Transaction\Transaction",$expectedExceptions);
    }

    /**
     * Cancel a G Suite subscription - This will not cancel the subscription immediately
     * but instead schedule it to not be renewed at the next renewal date.
     *
     * @param  $domainName
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function cancel($domainName){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        parent::callMethod("$domainName", "DELETE", array(),null,null,$expectedExceptions);
    }

    /**
     * Restore a G Suite subscription which may have been cancelled in error.  Please note that this
     * can only be done before the renewal date after which a new subscription will need to be created.
     *
     * @param  $domainName
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function restore($domainName){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        parent::callMethod("$domainName", "PATCH", array(),null,null,$expectedExceptions);
    }

    /**
     * Suspend a G Suite subscription.  This would usually be in the scenario that a customer
     * hasn't paid.
     *
     * @param string $domainName
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function suspend($domainName){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        parent::callMethod("suspend/$domainName", "GET", array(),null,null,$expectedExceptions);
    }

    /**
     * Unsuspend a G Suite subscription which you have previously suspended.
     *
     * @param string $domainName
    * @throws \Netistrar\ClientAPI\Exception\RateLimitExceededException
     */
    public function unsuspend($domainName){
        $expectedExceptions = array();
        $expectedExceptions["\\Kinikit\\MVC\\Exception\\RateLimitExceededException"] = "\Netistrar\ClientAPI\Exception\RateLimitExceededException";
        parent::callMethod("unsuspend/$domainName", "GET", array(),null,null,$expectedExceptions);
    }


}

