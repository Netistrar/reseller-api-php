<?php

namespace Netistrar\ClientAPI\Controllers;

use Kinikit\Core\Util\HTTP\WebServiceProxy;

/**
 * The Test API provides for OTE testing purposes allowing data to be updated or changed directly, circumventing the usual rules in place.  This facilitates in particular transfer testing.
 *
 * 
*/
class test extends WebServiceProxy {

    /**
     * Update the account balance to a new value in UK Pounds.
     *
     * 
     * @param integer $newBalance
     */
    public function updateAccountBalance($newBalance){
        $expectedExceptions = array();
        parent::callMethod("updateBalance/$newBalance", "GET", array(),null,null,$expectedExceptions);
    }

    /**
     * Update attributes of domain name not usually accessible for testing purposes.
     *
     * 
     * @param \Netistrar\ClientAPI\Objects\Test\Domain\TestDomainNameUpdateDescriptor $testDomainNameUpdateDescriptor
     * @return boolean
    * @throws \Netistrar\ClientAPI\Exception\TransactionException
     */
    public function updateDomains($testDomainNameUpdateDescriptor){
        $expectedExceptions = array();
        $expectedExceptions["\Netistrar\WebServices\Common\Exception\TransactionException"] = "\Netistrar\ClientAPI\Exception\TransactionException";
        return parent::callMethod("updateDomains", "PUT", array(),$testDomainNameUpdateDescriptor,"boolean",$expectedExceptions);
    }

    /**
     * Create one or more .RODEO domains in a different Registrar account, unlocked and ready for a pull transfer.
     *
     * You can create up to 10 domains using this method and it will return an array of entries where each entry is itself a simple array containing the domain name created
     * and the authorisation code required to start the transfer.
     *
     * 
     * @param integer $numberOfDomains
     * @return string[][]
     */
    public function createPullTransferRodeoDomains($numberOfDomains){
        $expectedExceptions = array();
        return parent::callMethod("createPullTransfer/$numberOfDomains", "GET", array(),null,"string[][]",$expectedExceptions);
    }

    /**
     * Create one or more UK domains in the Netistrar Registrar account as if they had just been TAG changed to Netistrar.  This allows for testing of the push transfer in logic.<br /><br />
     * You can create up to 10 domains using this method and it will return an array of string domain names for the test domains created
     *
     * 
     * @param integer $numberOfDomains
     * @return string[]
     */
    public function createPushTransferUKDomains($numberOfDomains){
        $expectedExceptions = array();
        return parent::callMethod("createPushTransfer/$numberOfDomains", "GET", array(),null,"string[]",$expectedExceptions);
    }

    /**
     * Start a transfer out to a different Registrar account for one or more .RODEO domain names currently in your account.  This allows for testing of the Transfer out workflow for
     * Pull transfer domains.
     *
     * 
     * @param string[] $domainNames
     */
    public function startTransferOutRodeo($domainNames){
        $expectedExceptions = array();
        parent::callMethod("startTransferOut", "POST", array(),$domainNames,null,$expectedExceptions);
    }

    /**
     * Accept Ownership confirmation for a transfer for a set of domain names (either .RODEO or .UK) which have been started for transfer in / out
     * using the <i>createIncomingTransferDomains</i> method on the <a href="netistrar-domain-transfer-api">Domain API</a> or
     * using the <b>startTransferOutRodeo</b> method above respectively.
     *
     * This is equivalent to clicking the links sent via email to the owner to confirm that the transfer can proceed.
     *
     * In the case of an incoming transfer this will start the transfer operation at the Registry.
     *
     * In the case of an outgoing transfer this will accept the transfer operation started by the <a href="#startTransferOutForPullTransferRodeoDomains">startTransferOutForPullTransferRodeoDomains</a> method.
     *
     * 
     * @param string[] $domainNames
     */
    public function acceptOwnershipConfirmation($domainNames){
        $expectedExceptions = array();
        parent::callMethod("acceptOwnershipConfirmation", "POST", array(),$domainNames,null,$expectedExceptions);
    }

    /**
     * Decline Ownership confirmation for a transfer for a set of domain names (either .RODEO or .UK) which have been started for transfer in / out
     * using the <i>createIncomingTransferDomains</i> method on the <a href="netistrar-domain-transfer-api">Domain API</a> or
     * using the <b>startTransferOutRodeo</b> method above respectively.
     *
     * This is equivalent to clicking the links sent via email to the owner to decline  the transfer .
     *
     * In the case of an incoming transfer this will abandon the incoming transfer and restore the domain to active state.
     *
     * In the case of an outgoing transfer this will reject the operation started by the <b>startTransferOutForPullTransferRodeoDomains</b> method.
     *
     * 
     * @param string[] $domainNames
     */
    public function declineOwnershipConfirmation($domainNames){
        $expectedExceptions = array();
        parent::callMethod("declineOwnershipConfirmation", "POST", array(),$domainNames,null,$expectedExceptions);
    }

    /**
     * Accepts incoming pull transfers at the other registrar for a set of pull transfer Rodeo domains.  This should be called after a call is made to the <b">createPullTransferRodeoDomains</b> to expedite the
     * transfer in operation at the other end for testing.
     *
     * 
     * @param string[] $domainNames
     */
    public function approveIncomingTransferOtherRegistrar($domainNames){
        $expectedExceptions = array();
        parent::callMethod("approveIncomingTransfer", "POST", array(),$domainNames,null,$expectedExceptions);
    }

    /**
     * Rejects incoming pull transfers at the other registrar for a set of pull transfer Rodeo domains.  This should be called after a call is made to the <a href="#approveOwnershipConfirmationForTransfer">approveOwnershipConfirmationForTransfer</a> to reject the
     * transfer in operation at the other end for testing
     *
     * 
     * @param string[] $domainNames
     */
    public function rejectIncomingTransferOtherRegistrar($domainNames){
        $expectedExceptions = array();
        parent::callMethod("rejectIncomingTransfer", "POST", array(),$domainNames,null,$expectedExceptions);
    }


}
