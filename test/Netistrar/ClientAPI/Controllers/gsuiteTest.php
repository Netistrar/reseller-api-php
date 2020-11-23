<?php
/**
 * Created by PhpStorm.
 * User: markrobertshaw
 * Date: 24/10/2018
 * Time: 16:20
 */

namespace Netistrar\ClientAPI\Controllers;


use Netistrar\ClientAPI\Exception\RateLimitExceededException;
use Netistrar\ClientAPI\Objects\GSuite\Descriptor\GSuiteCreateDescriptor;
use Netistrar\ClientAPI\Objects\Transaction\Transaction;
use Netistrar\ClientAPI\Objects\Transaction\TransactionElement;
use Netistrar\ClientAPI\Objects\Utility\BulkOperationProgress;

include_once "ClientAPITestBase.php";

class gsuiteTest extends \ClientAPITestBase {

    public function testCanCheckAvailabilityForAGSuiteDomain() {

        $availability = $this->api->gsuite()->available("oxil.co.uk");
        $this->assertEquals("UNAVAILABLE", $availability);

        $availability = $this->api->gsuite()->available("mysausagedog12345");
        $this->assertEquals("AVAILABLE", $availability);

    }


    public function testTransactionErrorsReturnedIfAttemptToCreateGSuiteForDomainNameNotInAccount() {

        $result = $this->api->gsuite()->create("none-existent.com", new GSuiteCreateDescriptor());

        $this->assertTrue($result instanceof Transaction);
        $this->assertEquals("ALL_ELEMENTS_FAILED", $result->getTransactionStatus());
        $this->assertEquals(0, $result->getOrderTotal());
        $noneexistentcom = $result->getTransactionElements()["none-existent.com"];
        $this->assertTrue($noneexistentcom instanceof TransactionElement);
        $this->assertEquals("FAILED", $noneexistentcom->getElementStatus());
        $errors = $noneexistentcom->getElementErrors();
        $this->assertTrue(isset($errors["DOMAIN_NOT_IN_ACCOUNT"]));


    }




}
