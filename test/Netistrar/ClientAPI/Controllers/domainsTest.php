<?php


namespace Netistrar\ClientAPI\Controllers;


use DateInterval;
use DateTime;
use Kinikit\Core\Object\SerialisableObject;
use Kinikit\Core\Util\SerialisableArrayUtils;
use Netistrar\ClientAPI\Exception\TransactionException;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameCreateDescriptor;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameRenewDescriptor;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameTransferDescriptor;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameUpdateDescriptor;
use Netistrar\ClientAPI\Objects\Domain\DomainNameContact;
use Netistrar\ClientAPI\Objects\Domain\DomainNameDNSSECRecord;
use Netistrar\ClientAPI\Objects\Domain\DomainNameGlueRecord;
use Netistrar\ClientAPI\Objects\Domain\DomainNameListResults;
use Netistrar\ClientAPI\Objects\Domain\DomainNameObject;
use Netistrar\ClientAPI\Objects\Test\Domain\TestDomainNameUpdateDescriptor;
use Netistrar\ClientAPI\Objects\Transaction\Transaction;
use Netistrar\ClientAPI\Objects\Transaction\TransactionElement;
use Netistrar\ClientAPI\Objects\Transaction\TransactionError;
use Netistrar\ClientAPI\Objects\Utility\BulkOperationProgress;


include_once "ClientAPITestBase.php";


class domainsTest extends \ClientAPITestBase {

    /**
     * Check that we can validate a domain name contact
     */
    public function testCanValidateCreateOperationForMultipleDomains() {

        $ukDomain = "validationdomain.uk";
        $comDomain = "validationdomain.com";
        $owner = new DomainNameContact();

        $createDescriptor = new DomainNameCreateDescriptor(array($ukDomain, $comDomain), 1, $owner, array("monkeynameserver"));

        $validationErrors = $this->api->domains()->validate($createDescriptor);

        $this->assertTrue(is_array($validationErrors[$ukDomain]));
        $this->assertTrue(isset($validationErrors[$ukDomain]["DOMAIN_INVALID_OWNER_CONTACT"]));
        $this->assertTrue(isset($validationErrors[$ukDomain]["DOMAIN_INVALID_NAMESERVER_FORMAT"]));

        $this->assertTrue(is_array($validationErrors[$comDomain]));
        $this->assertTrue(isset($validationErrors[$comDomain]["DOMAIN_INVALID_OWNER_CONTACT"]));
        $this->assertTrue(isset($validationErrors[$comDomain]["DOMAIN_INVALID_NAMESERVER_FORMAT"]));

        $this->assertTrue($validationErrors[$comDomain]["DOMAIN_INVALID_NAMESERVER_FORMAT"] instanceof TransactionError);

    }


    public function testFailedTransactionIsReturnedIfValidationErrorsOnCreate() {

        $ukDomain = "validationdomain.uk";
        $comDomain = "validationdomain.com";
        $owner = new DomainNameContact();


        // Try a single one.
        $domainTransaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($ukDomain), 1, $owner, array("monkeynameserver")));


        $this->assertTrue($domainTransaction instanceof Transaction);

        $this->assertEquals(1, sizeof($domainTransaction->getTransactionElements()));

        // Expected
        $element = $domainTransaction->getTransactionElements()[$ukDomain];

        $validationErrors = $element->getElementErrors();
        $this->assertTrue(is_array($validationErrors));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_OWNER_CONTACT"]));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_NAMESERVER_FORMAT"]));


        // Try a multiple one.
        $domainTransaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($ukDomain, $comDomain), 1, $owner, array("monkeynameserver")));

        $this->assertTrue($domainTransaction instanceof Transaction);

        $this->assertEquals(2, sizeof($domainTransaction->getTransactionElements()));

        // Expected
        $element = $domainTransaction->getTransactionElements()[$ukDomain];
        $validationErrors = $element->getElementErrors();
        $this->assertTrue(is_array($validationErrors));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_OWNER_CONTACT"]));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_NAMESERVER_FORMAT"]));

        $element = $domainTransaction->getTransactionElements()[$comDomain];
        $validationErrors = $element->getElementErrors();
        $this->assertTrue(is_array($validationErrors));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_OWNER_CONTACT"]));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_NAMESERVER_FORMAT"]));

    }


    public function testValidUnavailableAndMyDomainsReturnTransactionWithOperationErrors() {

        $existingDomain = "ganymede-_netistrar.co.uk";
        $myDomain = "max63sagreatbigridiculouslylongdomainnamebutthatiswhatineed1234.xyz";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($existingDomain, $myDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), $owner, $owner, $owner));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $elementErrors = $transaction->getTransactionElements()[$existingDomain]->getElementErrors();
        $this->assertEquals(1, sizeof($elementErrors));
        $this->assertTrue(isset($elementErrors["DOMAIN_UNAVAILABLE_FOR_REGISTRATION"]));

        $elementErrors = $transaction->getTransactionElements()[$myDomain]->getElementErrors();
        $this->assertEquals(1, sizeof($elementErrors));
        $this->assertTrue(isset($elementErrors["DOMAIN_ALREADY_IN_ACCOUNT"]));

    }

//    public function testOperationErrorsOccurIfUnexpectedCreateError() {
//
//        $rightsUK = "ganymede-netistrar.uk";
//
//        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "myorg", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
//        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
//
//        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($rightsUK), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com")));
//
//        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
//        $this->assertNotNull($transaction->getTransactionDateTime());
//        $this->assertNotNull($transaction->getOrderId());
//        $this->assertEquals("GBP", $transaction->getOrderCurrency());
//        $this->assertEquals(0, $transaction->getOrderSubtotal());
//        $this->assertEquals(0, $transaction->getOrderTaxes());
//        $this->assertEquals(0, $transaction->getOrderTotal());
//
//        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
//        $elements = $transaction->getTransactionElements();
//        $element = $elements[$rightsUK];
//        $this->assertEquals($rightsUK, $element->getDescription());
//        $this->assertEquals("FAILED", $element->getElementStatus());
//        $this->assertEquals(array(), $element->getOperationData());
//        $this->assertEquals(0, $element->getOrderLineSubtotal());
//        $this->assertEquals(0, $element->getOrderLineTaxes());
//        $this->assertEquals(0, $element->getOrderLineTotal());
//        $this->assertEquals(1, sizeof($element->getElementErrors()));
//
//
//        $elementError = $element->getElementErrors()["DOMAIN_REGISTRATION_ERROR"];
//        $this->assertTrue($elementError instanceof TransactionError);
//        $this->assertEquals("DOMAIN_REGISTRATION_ERROR", $elementError->getCode());
//
//
//        try {
//            // Now confirm that the registration didn't actually take place.
//            $domainName = $this->api->domains()->get($rightsUK);
//            $this->fail("Should have thrown here");
//        } catch (\Exception $e) {
//            // Success
//        }
//
//    }

    public function testCanCreateValidSingleUKDomainNameWithAllAssociatedAssetsAndTransactionIsReturned() {

        $newUKDomain = "validdomain-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $key = $this->api->utility()->createBulkOperation();

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1), $key);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_CREATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getOrderId());
        $this->assertEquals("GBP", $transaction->getOrderCurrency());
        $this->assertEquals(4.50, $transaction->getOrderSubtotal());
        $this->assertEquals(0.9, $transaction->getOrderTaxes());
        $this->assertEquals(5.40, $transaction->getOrderTotal());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();
        $element = $elements[$newUKDomain];
        $this->assertEquals($newUKDomain, $element->getDescription());
        $this->assertEquals("SUCCEEDED", $element->getElementStatus());
        $expiryDate = new \DateTime();
        $expiryDate->add(new \DateInterval("P1Y"));
        $this->assertEquals(array("registrationYears" => 1, "expiryDate" => $expiryDate->format("d/m/Y")), $element->getOperationData());
        $this->assertEquals(4.50, $element->getOrderLineSubtotal());
        $this->assertEquals(0.9, $element->getOrderLineTaxes());
        $this->assertEquals(5.40, $element->getOrderLineTotal());


        // Now confirm that the registration actually took place correctly.
        $domainName = $this->api->domains()->get($newUKDomain);
        $this->assertEquals($expiryDate->format("d/m/Y"), explode(" ", $domainName->getExpiryDate())[0]);
        $this->assertEquals("Marky Babes", $domainName->getOwnerContact()->getName());
        $this->assertEquals("IND", $domainName->getOwnerContact()->getAdditionalData()["nominetRegistrantType"]);
        $this->assertEquals(1, $domainName->getLocked());
        $this->assertNull($domainName->getLockedUntil());
        $this->assertEquals(1, $domainName->getPrivacyProxy());
        $this->assertEquals(1, $domainName->getAutoRenew());


    }


    public function testDefaultValuesAreAssumedForOtherContactsWhenOnlyOwnerSupplied() {

        $newBlogDomain = "validdomain-" . date("U") . ".rodeo";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($newBlogDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 0, [], 1));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_CREATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


    }


    public function testWhenPrivacySettingSetToTwoThisIsCorrectlyReflectedOnCreate() {

        $newUKDomain = "validdomain-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $key = $this->api->utility()->createBulkOperation();

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, [], 1), $key);

        // Now confirm that the registration actually took place correctly.
        $domainName = $this->api->domains()->get($newUKDomain);
        $this->assertEquals("Marky Babes", $domainName->getOwnerContact()->getName());
        $this->assertEquals("IND", $domainName->getOwnerContact()->getAdditionalData()["nominetRegistrantType"]);
        $this->assertEquals(1, $domainName->getLocked());
        $this->assertNull($domainName->getLockedUntil());
        $this->assertEquals(0, $domainName->getPrivacyProxy());
        $this->assertEquals(1, $domainName->getAutoRenew());
    }


    public function testOperationErrorReturnedAtTheTransactionLevelIfPaymentFailsForCreate() {

        $this->api->test()->updateAccountBalance(1);

        $newUKDomain = "validdomain-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com")));

        $this->assertEquals("DOMAIN_CREATE", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getTransactionDateTime());

        $this->assertNull($transaction->getOrderId());
        $this->assertNull($transaction->getOrderCurrency());
        $this->assertNull($transaction->getOrderSubtotal());
        $this->assertNull($transaction->getOrderTaxes());
        $this->assertNull($transaction->getOrderTotal());

        $this->assertEquals(0, sizeof($transaction->getTransactionElements()));
        $this->assertTrue($transaction->getTransactionError() instanceof TransactionError);
        $this->assertEquals("PAYMENT_ERROR", $transaction->getTransactionError()->getCode());

        $this->api->test()->updateAccountBalance(10000);

    }


    public function testRenewFailsWithValidationErrorIfTooManyYearsAttemptedForAdd() {

        // Create a couple of new domains
        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $transaction = $this->api->domains()->renewMultiple(new DomainNameRenewDescriptor(array($newUKDomain1, $newUKDomain2), 10));

        $this->assertTrue($transaction instanceof Transaction);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertNull($transaction->getOrderId());
        $this->assertNull($transaction->getOrderCurrency());
        $this->assertNull($transaction->getOrderSubtotal());
        $this->assertNull($transaction->getOrderTaxes());
        $this->assertNull($transaction->getOrderTotal());

        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();
        $element = $elements[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element->getDescription());
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertTrue(isset($element->getElementErrors()["DOMAIN_TOO_MANY_REGISTRATION_YEARS"]));

        $element = $elements[$newUKDomain2];
        $this->assertEquals($newUKDomain2, $element->getDescription());
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertTrue(isset($element->getElementErrors()["DOMAIN_TOO_MANY_REGISTRATION_YEARS"]));

    }


    public function testRenewFailsWithValidationErrorIfDomainNotInAccountAttemptedForRenewal() {

        // Create a couple of new domains
        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";

        $transaction = $this->api->domains()->renewMultiple(new DomainNameRenewDescriptor(array($newUKDomain1, $newUKDomain2), 1));


        $this->assertTrue($transaction instanceof Transaction);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertNull($transaction->getOrderId());
        $this->assertNull($transaction->getOrderCurrency());
        $this->assertNull($transaction->getOrderSubtotal());
        $this->assertNull($transaction->getOrderTaxes());
        $this->assertNull($transaction->getOrderTotal());

        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();
        $element = $elements[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element->getDescription());
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertTrue(isset($element->getElementErrors()["DOMAIN_NOT_IN_ACCOUNT"]));

        $element = $elements[$newUKDomain2];
        $this->assertEquals($newUKDomain2, $element->getDescription());
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertTrue(isset($element->getElementErrors()["DOMAIN_NOT_IN_ACCOUNT"]));

    }


    public function testRenewFailsWithValidationErrorIfDomainWithInvalidStatusAttemptedForRenewal() {

        // Create a couple of new domains
        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));


        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($newUKDomain1), "RGP"));

        $transaction = $this->api->domains()->renewMultiple(new DomainNameRenewDescriptor(array($newUKDomain1), 1));

        $this->assertTrue($transaction instanceof Transaction);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertNull($transaction->getOrderId());
        $this->assertNull($transaction->getOrderCurrency());
        $this->assertNull($transaction->getOrderSubtotal());
        $this->assertNull($transaction->getOrderTaxes());
        $this->assertNull($transaction->getOrderTotal());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();
        $element = $elements[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element->getDescription());
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertTrue(isset($element->getElementErrors()["DOMAIN_INVALID_FOR_RENEWAL"]));


    }


    public function testCanRenewValidMultipleDomains() {

        // Create a couple of new domains
        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));


        // Renew these domains
        $bulkKey = $this->api->utility()->createBulkOperation();

        $transaction = $this->api->domains()->renewMultiple(new DomainNameRenewDescriptor(array(
            $newUKDomain1, $newUKDomain2), 2), $bulkKey);


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_RENEW", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getOrderId());
        $this->assertEquals("GBP", $transaction->getOrderCurrency());
        $this->assertEquals(16.50, $transaction->getOrderSubtotal());
        $this->assertEquals(3.30, $transaction->getOrderTaxes());
        $this->assertEquals(19.80, $transaction->getOrderTotal());

        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();

        $element = $elements[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element->getDescription());
        $this->assertEquals("SUCCEEDED"
            , $element->getElementStatus());
        $expiryDate = new \DateTime();
        $expiryDate->add(new \DateInterval("P3Y"));
        $this->assertEquals(array("registrationYears" => 2, "expiryDate" => $expiryDate->format("d/m/Y")), $element->getOperationData());
        $this->assertEquals(8.25, $element->getOrderLineSubtotal());
        $this->assertEquals(1.65, $element->getOrderLineTaxes());
        $this->assertEquals(9.90, $element->getOrderLineTotal());

        $element = $elements[$newUKDomain2];
        $this->assertEquals($newUKDomain2, $element->getDescription());
        $this->assertEquals("SUCCEEDED"
            , $element->getElementStatus());
        $expiryDate = new DateTime();
        $expiryDate->add(new DateInterval("P3Y"));
        $this->assertEquals(array("registrationYears" => 2, "expiryDate" => $expiryDate->format("d/m/Y")), $element->getOperationData());
        $this->assertEquals(8.25, $element->getOrderLineSubtotal());
        $this->assertEquals(1.65, $element->getOrderLineTaxes());
        $this->assertEquals(9.90, $element->getOrderLineTotal());


        // Check updated expiry data.
        $domainName = $this->api->domains()->get($newUKDomain1);
        $this->assertEquals($expiryDate->format("d/m/Y"), explode(" ", $domainName->getExpiryDate())[0]);

        $domainName = $this->api->domains()->get($newUKDomain2);
        $this->assertEquals($expiryDate->format("d/m/Y"), explode(" ", $domainName->getExpiryDate())[0]);

    }


    public function testCanRenewSingleDomain() {

        // Create a couple of new domains
        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $transaction = $this->api->domains()->renew($newUKDomain1, 2);


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_RENEW", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getOrderId());
        $this->assertEquals("GBP", $transaction->getOrderCurrency());
        $this->assertEquals(8.25, $transaction->getOrderSubtotal());
        $this->assertEquals(1.65, $transaction->getOrderTaxes());
        $this->assertEquals(9.90, $transaction->getOrderTotal());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();

        $element = $elements[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element->getDescription());
        $this->assertEquals("SUCCEEDED"
            , $element->getElementStatus());
        $expiryDate = new \DateTime();
        $expiryDate->add(new \DateInterval("P3Y"));
        $this->assertEquals(array("registrationYears" => 2, "expiryDate" => $expiryDate->format("d/m/Y")), $element->getOperationData());
        $this->assertEquals(8.25, $element->getOrderLineSubtotal());
        $this->assertEquals(1.65, $element->getOrderLineTaxes());
        $this->assertEquals(9.90, $element->getOrderLineTotal());

    }


    public function testOperationErrorsAreReturnedIfAnAttemptToUpdateDomainForDomainNamesNotInAccount() {

        $newContact = new DomainNameContact("Oxil Chocs", "oxil@mylanding.com", null, null, null, "Oxfordshire", "OX4 6DG", "GB");
        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array("bingo.com", "bingo.org"), $newContact));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $element1 = $transaction->getTransactionElements()["bingo.com"];
        $this->assertEquals("bingo.com", $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_NOT_IN_ACCOUNT"]));

        $element2 = $transaction->getTransactionElements()["bingo.org"];
        $this->assertEquals("bingo.org", $element2->getDescription());
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $this->assertEquals(1, sizeof($element2->getElementErrors()));
        $this->assertTrue(isset($element2->getElementErrors()["DOMAIN_NOT_IN_ACCOUNT"]));

    }


    public function testOperationErrorsAreReturnedIfAnAttemptToUpdateDomainForNonActiveDomainNames() {

        $testInactiveDomain = "inactivedomain-" . date("U") . ".rodeo";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($testInactiveDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        // Now make the domain inactive
        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($testInactiveDomain), "EXPIRED"));

        // Attempt an update of inactive domain
        $newContact = new DomainNameContact("Oxil Chocs", "oxil@mylanding.com", null, null, null, "Oxfordshire", "OX4 6DG", "GB");
        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($testInactiveDomain), $newContact));

        $element1 = $transaction->getTransactionElements()[$testInactiveDomain];
        $this->assertEquals($testInactiveDomain, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_INVALID_FOR_UPDATE"]));


        // Now make the domain inactive
        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($testInactiveDomain), "SUSPENDED"));

        // Attempt an update of inactive domain
        $newContact = new DomainNameContact("Oxil Chocs", "oxil@mylanding.com", null, null, null, "Oxfordshire", "OX4 6DG", "GB");
        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($testInactiveDomain), $newContact));

        $element1 = $transaction->getTransactionElements()[$testInactiveDomain];
        $this->assertEquals($testInactiveDomain, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_INVALID_FOR_UPDATE"]));

        // Now make the domain inactive
        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($testInactiveDomain), "RGP"));

        // Attempt an update of inactive domain
        $newContact = new DomainNameContact("Oxil Chocs", "oxil@mylanding.com", null, null, null, "Oxfordshire", "OX4 6DG", "GB");
        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($testInactiveDomain), $newContact));

        $element1 = $transaction->getTransactionElements()[$testInactiveDomain];
        $this->assertEquals($testInactiveDomain, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_INVALID_FOR_UPDATE"]));


    }


    public function testValidationErrorsAreReturnedWhenBulkUpdatingContactsForDomainNames() {

        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $newContact = new DomainNameContact("Oxil Chocs", "oxil@mylanding.com", "Bingo", null, null, null, "Oxfordshire", "OX4 6DG", "GB");

        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), $newContact));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $element1 = $transaction->getTransactionElements()[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_INVALID_OWNER_CONTACT"]));

        $subordinateErrors = $element1->getElementErrors()["DOMAIN_INVALID_OWNER_CONTACT"]->getRelatedErrors();

        $this->assertTrue(isset($subordinateErrors["CONTACT_MISSING_CITY"]));
        $this->assertTrue(isset($subordinateErrors["CONTACT_MISSING_NOMINETREGISTRANTTYPE"]));


    }


    public function testSuccessfulTransactionIsReturnedWhenContactUpdateSucceeds() {

        $newUKDomain = "validdomain-" . date("U") . ".uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $newOwner = new DomainNameContact("New Man", "new@oxil.co.uk", "My Business", "66 My Street", "Arbury", "Cambridge", "Cambs", "CB4 2JL", "FR", "+44", "18657878787", "123", "+44", "18657878787", "123", array("nominetRegistrantType" => "STAT"));

        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain), $newOwner));


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));

        $transactionElement = $transaction->getTransactionElements()[$newUKDomain];
        $this->assertEquals("SUCCEEDED"
            , $transactionElement->getElementStatus());

        // Now actually pull the contact and check the update
        $domainName = $this->api->domains()->get($newUKDomain);
        $ownerContact = $domainName->getOwnerContact();
        $this->assertEquals("New Man", $ownerContact->getName());
        $this->assertEquals("new@oxil.co.uk", $ownerContact->getEmailAddress());
        $this->assertEquals("66 My Street", $ownerContact->getStreet1());
        $this->assertEquals("Arbury", $ownerContact->getStreet2());
        $this->assertEquals("Cambridge", $ownerContact->getCity());
        $this->assertEquals("Cambs", $ownerContact->getCounty());
        $this->assertEquals("CB4 2JL", $ownerContact->getPostcode());
        $this->assertEquals("FR", $ownerContact->getCountry());
        $this->assertEquals("My Business", $ownerContact->getOrganisation());
        $this->assertEquals("+44", $ownerContact->getTelephoneDiallingCode());
        $this->assertEquals("18657878787", $ownerContact->getTelephone());
        $this->assertEquals("123", $ownerContact->getTelephoneExt());
        $this->assertEquals("+44", $ownerContact->getFaxDiallingCode());
        $this->assertEquals("18657878787", $ownerContact->getFax());
        $this->assertEquals("123", $ownerContact->getFaxExt());
        $this->assertEquals(array("nominetRegistrantType" => "STAT"), $ownerContact->getAdditionalData());


    }


    public function testValidationErrorsOccurIfBulkUpdatingNameserversForInvalidNames() {

        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), null, null, null, null, array("22.45.66.77", "rubbishdnsname")));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $element1 = $transaction->getTransactionElements()[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_INVALID_NAMESERVER_FORMAT"]));


        $element2 = $transaction->getTransactionElements()[$newUKDomain2];
        $this->assertEquals($newUKDomain2, $element2->getDescription());
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $this->assertEquals(1, sizeof($element2->getElementErrors()));
        $this->assertTrue(isset($element2->getElementErrors()["DOMAIN_INVALID_NAMESERVER_FORMAT"]));


    }


    public function testValidationErrorsOccurIfBulkUpdatingNameserverForDuplicateNames() {

        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), null, null, null, null, array("ns1.oxil.com", "ns1.oxil.com")));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $element1 = $transaction->getTransactionElements()[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_DUPLICATE_NAMESERVER"]));


        $element2 = $transaction->getTransactionElements()[$newUKDomain2];
        $this->assertEquals($newUKDomain2, $element2->getDescription());
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $this->assertEquals(1, sizeof($element2->getElementErrors()));
        $this->assertTrue(isset($element2->getElementErrors()["DOMAIN_DUPLICATE_NAMESERVER"]));


    }


    public function testSuccessfulTransactionIsReturnedWhenNameserverUpdateSucceeds() {


        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), null, null, null, null, array("ns1.oxil.com", "ns2.oxil.com", "ns3.oxil.com")));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $transactionElement = $transaction->getTransactionElements()[$newUKDomain1];
        $this->assertEquals("SUCCEEDED"
            , $transactionElement->getElementStatus());

        $transactionElement = $transaction->getTransactionElements()[$newUKDomain2];
        $this->assertEquals("SUCCEEDED"
            , $transactionElement->getElementStatus());


        // Now actually pull the domain and check the update
        $domainName = $this->api->domains()->get
        ($newUKDomain1);
        $ns = $domainName->getNameservers();
        $this->assertEquals("ns1.oxil.com", $ns[0]);
        $this->assertEquals("ns2.oxil.com", $ns[1]);
        $this->assertEquals("ns3.oxil.com", $ns[2]);


        $domainName = $this->api->domains()->get
        ($newUKDomain2);
        $ns = $domainName->getNameservers();
        $this->assertEquals("ns1.oxil.com", $ns[0]);
        $this->assertEquals("ns2.oxil.com", $ns[1]);
        $this->assertEquals("ns3.oxil.com", $ns[2]);


    }


    public function testValidationErrorsOccurIfBulkUpdatingLockedStatusToUnlockedWhenMandatoryLockInPlace() {

        $newUKDomain1 = "validdomain-" . date("U") . ".rodeo";
        $newUKDomain2 = "validdomain-" . date("U") . "1.rodeo";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), null, null, null, null, null, 0));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $element1 = $transaction->getTransactionElements()[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(1, sizeof($element1->getElementErrors()));
        $this->assertTrue(isset($element1->getElementErrors()["DOMAIN_IN_MANDATORY_LOCK"]));


        $element2 = $transaction->getTransactionElements()[$newUKDomain2];
        $this->assertEquals($newUKDomain2, $element2->getDescription());
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $this->assertEquals(1, sizeof($element2->getElementErrors()));
        $this->assertTrue(isset($element2->getElementErrors()["DOMAIN_IN_MANDATORY_LOCK"]));

    }


    public function testCanUpdateValidDomainAttributesCorrectlyForDomainNames() {

        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($newUKDomain1), null, null, null));

        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), null, null, null, null, null, false, false, true));

        $this->assertTrue($transaction instanceof Transaction);


        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $transactionElement = $transaction->getTransactionElements()[$newUKDomain1];
        $this->assertEquals("SUCCEEDED"
            , $transactionElement->getElementStatus());

        $transactionElement = $transaction->getTransactionElements()[$newUKDomain2];
        $this->assertEquals("SUCCEEDED"
            , $transactionElement->getElementStatus());

        // Now actually pull the domain and check the update
        $domainName = $this->api->domains()->get($newUKDomain1);
        $this->assertEquals(0, $domainName->getLocked());
        $this->assertEquals(0, $domainName->getPrivacyProxy());
        $this->assertEquals(1, $domainName->getAutoRenew());


        $domainName = $this->api->domains()->get($newUKDomain2);
        $this->assertEquals(0, $domainName->getLocked());
        $this->assertEquals(0, $domainName->getPrivacyProxy());
        $this->assertEquals(1, $domainName->getAutoRenew());

        // Now try and set privacy proxy to 1
        $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), null, null, null, null, null, null, 1, null));

        // Now actually pull the domain and check the update
        $domainName = $this->api->domains()->get($newUKDomain1);
        $this->assertEquals(0, $domainName->getLocked());
        $this->assertEquals(1, $domainName->getPrivacyProxy());
        $this->assertEquals(1, $domainName->getAutoRenew());
    }


    public function testTransactionProgressForUpdatesAreMonitoredCorrectlyWhenProgressKeyPassed() {

        $newUKDomain1 = "validdomain-" . date("U") . ".rodeo";
        $newUKDomain2 = "validdomain-" . date("U") . "1.rodeo";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));

        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($newUKDomain1), null, "_UNSET", "_UNSET"));

        $progressKey = $this->api->utility()->createBulkOperation();

        // Run transaction
        $transaction = $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain1, $newUKDomain2), null, null, null, null, null, 0, 0, 1), $progressKey);

        $this->assertTrue($transaction instanceof Transaction);

        $this->assertEquals("DOMAIN_UPDATE", $transaction->getTransactionType());
        $this->assertEquals("PARTIALLY_SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        // Also, check the bulk operation
        $bulkOperationProgress = $this->api->utility()->getBulkOperationProgress($progressKey);
        $this->assertEquals("COMPLETED", $bulkOperationProgress->getStatus());
        $this->assertEquals("SUCCEEDED", $bulkOperationProgress->getProgressItems()[0]->getStatus());
        $this->assertEquals("FAILED", $bulkOperationProgress->getProgressItems()[1]->getStatus());


    }


    public function testCanGetMultipleDomainNamesIfValid() {

        // Firstly, attempt to get invalid domains with ignore set to false.
        try {
            $this->api->domains()->getMultiple(array("biggles.com", "boggles.org"), 0);
            $this->fail("Should have thrown an exception here.");
        } catch (TransactionException $e) {


            $this->assertEquals(1, sizeof($e->getTransactionErrors()));
            $this->assertTrue(isset($e->getTransactionErrors()["DOMAIN_NOT_IN_ACCOUNT"]));
            $this->assertEquals(array("missingDomainNames" => array("biggles.com", "boggles.org")), $e->getTransactionErrors()["DOMAIN_NOT_IN_ACCOUNT"]->getAdditionalInfo());
        }


        $newUKDomain1 = "validdomain-" . date("U") . ".uk";
        $newUKDomain2 = "validdomain-" . date("U") . "1.uk";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", "", "Oxford", "Oxon", "OX4 2RD", "GB", "", "", "", "", "", "", "", array());
        $owner->__setSerialisablePropertyValue("status", "LIVE");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain1, $newUKDomain2), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1));


        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($newUKDomain1), null, "_UNSET", "_UNSET", 0));

        // Check that with ignore set to true, only valid domains are returned.
        $domains = $this->api->domains()->getMultiple(array("biggles.com", "boggles.org"), 1);
        $this->assertEquals(0, sizeof($domains));


        // Now check we can get the domain names as expected.
        $domainNames = $this->api->domains()->getMultiple(array($newUKDomain1, $newUKDomain2));
        $this->assertEquals(2, sizeof($domainNames));

        $expiryDate = new DateTime();
        $expiryDate->add(new DateInterval("P1Y"));

        $this->assertTrue($domainNames[$newUKDomain1] instanceof DomainNameObject);
        $domainName = $domainNames[$newUKDomain1];
        $this->assertEquals($newUKDomain1, $domainName->getDomainName());
        $this->assertEquals(array("ns1.netistrar.com", "ns2.netistrar.com"), $domainName->getNameservers());
        $this->assertEquals($owner, $domainName->getOwnerContact());
        $this->assertEquals(null, $domainName->getAdminContact());
        $this->assertEquals(null, $domainName->getBillingContact());
        $this->assertEquals(null, $domainName->getTechnicalContact());
        $this->assertEquals($expiryDate->format("d/m/Y"), substr($domainName->getExpiryDate(), 0, 10));
        $this->assertEquals(0, $domainName->getAutoRenew());
        $this->assertEquals(0, $domainName->getLocked());
        $this->assertEquals(1, $domainName->getPrivacyProxy());
        $this->assertNotEquals("N/A", $domainName->getAuthCode());

        $this->assertTrue($domainNames[$newUKDomain2] instanceof DomainNameObject);
        $domainName = $domainNames[$newUKDomain2];
        $this->assertEquals($newUKDomain2, $domainName->getDomainName());
        $this->assertEquals(date("d/m/Y"), substr($domainName->getRegisteredDate(), 0, 10));
        $this->assertEquals(array("ns1.netistrar.com", "ns2.netistrar.com"), $domainName->getNameservers());
        $this->assertEquals($owner, $domainName->getOwnerContact());
        $this->assertEquals(null, $domainName->getAdminContact());
        $this->assertEquals(null, $domainName->getBillingContact());
        $this->assertEquals(null, $domainName->getTechnicalContact());
        $this->assertEquals($expiryDate->format("d/m/Y"), substr($domainName->getExpiryDate(), 0, 10));
        $this->assertEquals(0, $domainName->getAutoRenew());
        $this->assertEquals(1, $domainName->getLocked());
        $this->assertNull($domainName->getLockedUntil());
        $this->assertEquals("N/A", $domainName->getAuthCode());
        $this->assertEquals(1, $domainName->getPrivacyProxy());


    }


    public function testCanListDomains() {


        $listDomain1 = "zzzz-domain" . date("U") . ".uk";
        $listDomain2 = "zzzz-domain" . date("U") . "1.uk";
        $listDomain3 = "zzzz-domain" . date("U") . "2.uk";
        $listDomain4 = "aaaa-domain" . date("U") . ".uk";
        $listDomain5 = "aaaa-domain" . date("U") . "1.uk";
        $listDomain6 = "aaaa-domain" . date("U") . "2.uk";


        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($listDomain1, $listDomain2, $listDomain3, $listDomain4, $listDomain5, $listDomain6), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, true));


        // Try a reverse order list
        $listedDomains = $this->api->domains()->list("", 10, 1, "domainName", "DESC");

        $this->assertTrue($listedDomains instanceof DomainNameListResults);
        $this->assertEquals("", $listedDomains->getSearchTerm());
        $this->assertEquals(10, $listedDomains->getPageSize());
        $this->assertEquals(1, $listedDomains->getPage());
        $this->assertEquals("domainName", $listedDomains->getOrderBy());
        $this->assertEquals("DESC", $listedDomains->getOrderDirection());
        $this->assertTrue($listedDomains->getTotalNumberOfDomains() > 10);
        $this->assertTrue($listedDomains->getTotalNumberOfPages() > 1);
        $this->assertEquals(10, sizeof($listedDomains->getDomainNameSummaries()));
        $this->assertEquals($listDomain3, $listedDomains->getDomainNameSummaries()[0]->getDomainName());
        $this->assertEquals($listDomain2, $listedDomains->getDomainNameSummaries()[1]->getDomainName());
        $this->assertEquals($listDomain1, $listedDomains->getDomainNameSummaries()[2]->getDomainName());

        // Try a paged list
        $listedDomains = $this->api->domains()->list("", 1, null, "domainName", "DESC");

        $this->assertTrue($listedDomains instanceof DomainNameListResults);
        $this->assertEquals("", $listedDomains->getSearchTerm());
        $this->assertEquals(1, $listedDomains->getPageSize());
        $this->assertEquals(1, $listedDomains->getPage());
        $this->assertEquals("domainName", $listedDomains->getOrderBy());
        $this->assertEquals("DESC", $listedDomains->getOrderDirection());
        $this->assertTrue($listedDomains->getTotalNumberOfDomains() > 10);
        $this->assertTrue($listedDomains->getTotalNumberOfPages() > 1);
        $this->assertEquals(1, sizeof($listedDomains->getDomainNameSummaries()));
        $this->assertEquals($listDomain3, $listedDomains->getDomainNameSummaries()[0]->getDomainName());

        // Page 2
        $listedDomains = $this->api->domains()->list("", 1, 2, "domainName", "DESC");
        $this->assertTrue($listedDomains instanceof DomainNameListResults);
        $this->assertEquals("", $listedDomains->getSearchTerm());
        $this->assertEquals(1, $listedDomains->getPageSize());
        $this->assertEquals(2, $listedDomains->getPage());
        $this->assertEquals("domainName", $listedDomains->getOrderBy());
        $this->assertEquals("DESC", $listedDomains->getOrderDirection());
        $this->assertTrue($listedDomains->getTotalNumberOfDomains() > 10);
        $this->assertTrue($listedDomains->getTotalNumberOfPages() > 1);
        $this->assertEquals(1, sizeof($listedDomains->getDomainNameSummaries()));
        $this->assertEquals($listDomain2, $listedDomains->getDomainNameSummaries()[0]->getDomainName());

        // Bigger page size
        $listedDomains = $this->api->domains()->list("", 2, 1, "domainName", "DESC");
        $this->assertTrue($listedDomains instanceof DomainNameListResults);
        $this->assertEquals("", $listedDomains->getSearchTerm());
        $this->assertEquals(2, $listedDomains->getPageSize());
        $this->assertEquals(1, $listedDomains->getPage());
        $this->assertEquals("domainName", $listedDomains->getOrderBy());
        $this->assertEquals("DESC", $listedDomains->getOrderDirection());
        $this->assertTrue($listedDomains->getTotalNumberOfDomains() > 10);
        $this->assertTrue($listedDomains->getTotalNumberOfPages() > 1);
        $this->assertEquals(2, sizeof($listedDomains->getDomainNameSummaries()));
        $this->assertEquals($listDomain3, $listedDomains->getDomainNameSummaries()[0]->getDomainName());
        $this->assertEquals($listDomain2, $listedDomains->getDomainNameSummaries()[1]->getDomainName());

        // Page 2
        $listedDomains = $this->api->domains()->list("", 2, 2, "domainName", "DESC");
        $this->assertTrue($listedDomains instanceof DomainNameListResults);
        $this->assertEquals("", $listedDomains->getSearchTerm());
        $this->assertEquals(2, $listedDomains->getPageSize());
        $this->assertEquals(2, $listedDomains->getPage());
        $this->assertEquals("domainName", $listedDomains->getOrderBy());
        $this->assertEquals("DESC", $listedDomains->getOrderDirection());
        $this->assertTrue($listedDomains->getTotalNumberOfDomains() > 10);
        $this->assertTrue($listedDomains->getTotalNumberOfPages() > 1);
        $this->assertEquals(2, sizeof($listedDomains->getDomainNameSummaries()));
        $this->assertEquals($listDomain1, $listedDomains->getDomainNameSummaries()[0]->getDomainName());

        // Different sort column
        $listedDomains = $this->api->domains()->list("", 10, 1, "registeredDate", "DESC");
        $this->assertTrue($listedDomains instanceof DomainNameListResults);
        $this->assertEquals("", $listedDomains->getSearchTerm());
        $this->assertEquals(10, $listedDomains->getPageSize());
        $this->assertEquals(1, $listedDomains->getPage());
        $this->assertEquals("registeredDate", $listedDomains->getOrderBy());
        $this->assertEquals("DESC", $listedDomains->getOrderDirection());
        $this->assertTrue($listedDomains->getTotalNumberOfDomains() > 10);
        $this->assertTrue($listedDomains->getTotalNumberOfPages() > 1);
        $this->assertEquals(10, sizeof($listedDomains->getDomainNameSummaries()));
        $listedSummaries = SerialisableArrayUtils::getMemberValueArrayForObjects("domainName", $listedDomains->getDomainNameSummaries());
        $this->assertTrue(in_array($listDomain1, $listedSummaries));
        $this->assertTrue(in_array($listDomain2, $listedSummaries));
        $this->assertTrue(in_array($listDomain3, $listedSummaries));
        $this->assertTrue(in_array($listDomain4, $listedSummaries));
        $this->assertTrue(in_array($listDomain5, $listedSummaries));
        $this->assertTrue(in_array($listDomain6, $listedSummaries));

        // Filtered search
        $listedDomains = $this->api->domains()->list("aaaa", 10, 1, "domainName", "DESC");
        $this->assertTrue($listedDomains instanceof DomainNameListResults);
        $this->assertEquals("aaaa", $listedDomains->getSearchTerm());
        $this->assertEquals(10, $listedDomains->getPageSize());
        $this->assertEquals(1, $listedDomains->getPage());
        $this->assertEquals("domainName", $listedDomains->getOrderBy());
        $this->assertEquals("DESC", $listedDomains->getOrderDirection());
        $this->assertTrue($listedDomains->getTotalNumberOfDomains() >= 3);
        $this->assertTrue($listedDomains->getTotalNumberOfPages() >= 1);
        $this->assertEquals($listDomain6, $listedDomains->getDomainNameSummaries()[0]->getDomainName());
        $this->assertEquals($listDomain5, $listedDomains->getDomainNameSummaries()[1]->getDomainName());
        $this->assertEquals($listDomain4, $listedDomains->getDomainNameSummaries()[2]->getDomainName());


    }


    public function testTransactionErrorRaisedIfNoneAccountOrNoneActiveDomainNameAttemptedForGlueRecordSet() {

        $transaction = $this->api->domains()->glueRecordsSet("nonexistent12344.com", array(new DomainNameGlueRecord("ns1", "89.44.55.55")));


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());
        $this->assertEquals("DOMAIN_NOT_IN_ACCOUNT", $transaction->getTransactionError());
        $this->assertEquals(0, sizeof($transaction->getTransactionElements()));

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));


        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($domainName), "EXPIRED"));

        $transaction = $this->api->domains()->glueRecordsSet($domainName, array(new DomainNameGlueRecord("ns1", "89.44.55.55")));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());
        $this->assertEquals("DOMAIN_INVALID_FOR_GLUE_RECORD", $transaction->getTransactionError());
        $this->assertEquals(0, sizeof($transaction->getTransactionElements()));
    }


    public function testBadlyFormedGlueRecordsAreReturnedWithValidationErrorsInTransactionOnSet() {

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));


        // Now try and set some badly formed glue records
        $transaction = $this->api->domains()->glueRecordsSet($domainName, array(new DomainNameGlueRecord(), new DomainNameGlueRecord("65464_U"), new DomainNameGlueRecord("ns1"),
            new DomainNameGlueRecord("ns2", "8787978"), new DomainNameGlueRecord("ns3", null, "4535345354")));


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $this->assertEquals(5, sizeof($transaction->getTransactionElements()));


        $element1 = $transaction->getTransactionElements()[""];
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $glueRecord = new DomainNameGlueRecord();
        $this->assertEquals($glueRecord->__toArray(), $element1->getOperationData());
        $this->assertEquals(array("GLUE_RECORD_MISSING_SUBDOMAIN", "GLUE_RECORD_MISSING_IP_ADDRESS"), array_keys($element1->getElementErrors()));


        $element2 = $transaction->getTransactionElements()["65464_U"];
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $glueRecord = new DomainNameGlueRecord();
        $glueRecord->setSubDomainPrefix("65464_U");
        $this->assertEquals($glueRecord->__toArray(), $element2->getOperationData());
        $this->assertEquals(array("GLUE_RECORD_INVALID_SUBDOMAIN", "GLUE_RECORD_MISSING_IP_ADDRESS"), array_keys($element2->getElementErrors()));


        $element3 = $transaction->getTransactionElements()["ns1"];
        $this->assertEquals("FAILED", $element3->getElementStatus());
        $glueRecord = new DomainNameGlueRecord();
        $glueRecord->setSubDomainPrefix("ns1");
        $this->assertEquals($glueRecord->__toArray(), $element3->getOperationData());
        $this->assertEquals(array("GLUE_RECORD_MISSING_IP_ADDRESS"), array_keys($element3->getElementErrors()));

        $element4 = $transaction->getTransactionElements()["ns2"];
        $this->assertEquals("FAILED", $element4->getElementStatus());
        $glueRecord = new DomainNameGlueRecord();
        $glueRecord->setSubDomainPrefix("ns2");
        $glueRecord->setIpv4Address("8787978");
        $this->assertEquals($glueRecord->__toArray(), $element4->getOperationData());
        $this->assertEquals(array("GLUE_RECORD_INVALID_IP4_ADDRESS"), array_keys($element4->getElementErrors()));


        $element5 = $transaction->getTransactionElements()["ns3"];
        $this->assertEquals("FAILED", $element5->getElementStatus());
        $glueRecord = new DomainNameGlueRecord();
        $glueRecord->setSubDomainPrefix("ns3");
        $glueRecord->setIpv6Address("4535345354");
        $this->assertEquals($glueRecord->__toArray(), $element5->getOperationData());
        $this->assertEquals(array("GLUE_RECORD_INVALID_IP6_ADDRESS"), array_keys($element5->getElementErrors()));


    }


    public function testValidGlueRecordsAreCreatedAndUpdatedOnSet() {

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));

        // Now set some good records.
        $transaction = $this->api->domains()->glueRecordsSet($domainName, array(new DomainNameGlueRecord("ns1", "88.44.33.22"), new DomainNameGlueRecord("ns2", "99.11.22.33"),
            new DomainNameGlueRecord("ns3", null, "00:0a:27:02:00:6b:97:8f"), new DomainNameGlueRecord("ns4", "77.22.33.44", "00:0a:27:02:00:6b:97:8f")));


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(4, sizeof($transaction->getTransactionElements()));
        $this->assertEquals("DOMAIN_NAME", $transaction->getTransactionElements()["ns1"]->getType());
        $this->assertEquals($domainName, $transaction->getTransactionElements()["ns1"]->getDescription());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionElements()["ns1"]->getElementStatus());
        $this->assertEquals(array("subDomainPrefix" => "ns1", "ipv4Address" => "88.44.33.22", "domainName" => null, "ipv6Address" => null), $transaction->getTransactionElements()["ns1"]->getOperationData());

        $glueRecords = $this->api->domains()->glueRecordsList($domainName);
        $this->assertEquals(4, sizeof($glueRecords));
        $this->assertEquals(array("ns1", "ns2", "ns3", "ns4"), SerialisableArrayUtils::getMemberValueArrayForObjects("subDomainPrefix", $glueRecords));
        $this->assertEquals(array("88.44.33.22", "99.11.22.33", null, "77.22.33.44"), SerialisableArrayUtils::getMemberValueArrayForObjects("ipv4Address", $glueRecords));
        $this->assertEquals(array(null, null, "00:0a:27:02:00:6b:97:8f", "00:0a:27:02:00:6b:97:8f"), SerialisableArrayUtils::getMemberValueArrayForObjects("ipv6Address", $glueRecords));


        // Now update some records.
        $transaction = $this->api->domains()->glueRecordsSet($domainName, array(new DomainNameGlueRecord("ns1", "18.44.33.22"), new DomainNameGlueRecord("ns2", "19.11.22.33"),
            new DomainNameGlueRecord("ns3", "11.11.11.11", "10:0a:27:02:00:6b:97:8f"), new DomainNameGlueRecord("ns4", "17.22.33.44", "10:0a:27:02:00:6b:97:8f")));


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(4, sizeof($transaction->getTransactionElements()));
        $this->assertEquals("DOMAIN_NAME", $transaction->getTransactionElements()["ns1"]->getType());
        $this->assertEquals($domainName, $transaction->getTransactionElements()["ns1"]->getDescription());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionElements()["ns1"]->getElementStatus());
        $this->assertEquals(array("subDomainPrefix" => "ns1", "ipv4Address" => "18.44.33.22", "domainName" => null, "ipv6Address" => null), $transaction->getTransactionElements()["ns1"]->getOperationData());


        $glueRecords = $this->api->domains()->glueRecordsList($domainName);
        $this->assertEquals(4, sizeof($glueRecords));
        $this->assertEquals(array("ns1", "ns2", "ns3", "ns4"), SerialisableArrayUtils::getMemberValueArrayForObjects("subDomainPrefix", $glueRecords));
        $this->assertEquals(array("18.44.33.22", "19.11.22.33", "11.11.11.11", "17.22.33.44"), SerialisableArrayUtils::getMemberValueArrayForObjects("ipv4Address", $glueRecords));
        $this->assertEquals(array(null, null, "10:0a:27:02:00:6b:97:8f", "10:0a:27:02:00:6b:97:8f"), SerialisableArrayUtils::getMemberValueArrayForObjects("ipv6Address", $glueRecords));


    }


    public function testAttemptsToRemoveGlueRecordsFromNonAccountOrInactiveDomainReturnsFailedTransaction() {

        $transaction = $this->api->domains()->glueRecordsRemove("nonexistent12344.com", array("ns1"));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_REMOVE", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());
        $this->assertEquals("DOMAIN_NOT_IN_ACCOUNT", $transaction->getTransactionError());
        $this->assertEquals(0, sizeof($transaction->getTransactionElements()));

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));

        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($domainName), "EXPIRED"));

        $transaction = $this->api->domains()->glueRecordsRemove($domainName, array("ns1"));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_REMOVE", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());
        $this->assertEquals("DOMAIN_INVALID_FOR_GLUE_RECORD", $transaction->getTransactionError());
        $this->assertEquals(0, sizeof($transaction->getTransactionElements()));

    }


    public function testNoneExistentGlueRecordsReturnElementFailuresOnRemove() {

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));

        $transaction = $this->api->domains()->glueRecordsRemove($domainName, array("ns1", "ns2", "ns3"));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_REMOVE", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $this->assertEquals(3, sizeof($transaction->getTransactionElements()));

        $element1 = $transaction->getTransactionElements()["ns1"];
        $this->assertEquals("DOMAIN_NAME", $element1->getType());
        $this->assertEquals($domainName, $element1->getDescription());
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(array("subdomain" => "ns1"), $element1->getOperationData());
        $this->assertEquals(array("DOMAIN_MISSING_GLUE_RECORD"), array_keys($element1->getElementErrors()));

        $element2 = $transaction->getTransactionElements()["ns2"];
        $this->assertEquals("DOMAIN_NAME", $element2->getType());
        $this->assertEquals($domainName, $element2->getDescription());
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $this->assertEquals(array("subdomain" => "ns2"), $element2->getOperationData());
        $this->assertEquals(array("DOMAIN_MISSING_GLUE_RECORD"), array_keys($element2->getElementErrors()));

        $element3 = $transaction->getTransactionElements()["ns3"];
        $this->assertEquals("DOMAIN_NAME", $element3->getType());
        $this->assertEquals($domainName, $element3->getDescription());
        $this->assertEquals("FAILED", $element3->getElementStatus());
        $this->assertEquals(array("subdomain" => "ns3"), $element3->getOperationData());
        $this->assertEquals(array("DOMAIN_MISSING_GLUE_RECORD"), array_keys($element3->getElementErrors()));


    }


    public function testValidExistentGlueRecordsAreRemovedCorrectly() {

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));

        $this->api->domains()->glueRecordsSet($domainName, array(new DomainNameGlueRecord("ns1", "88.32.3.44"), new DomainNameGlueRecord("ns2", "77.55.66.77")));

        $transaction = $this->api->domains()->glueRecordsRemove($domainName, array("ns1", "ns2", "ns3"));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_GLUE_RECORD_REMOVE", $transaction->getTransactionType());
        $this->assertEquals("PARTIALLY_SUCCEEDED", $transaction->getTransactionStatus());

        $this->assertEquals(3, sizeof($transaction->getTransactionElements()));
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionElements()["ns1"]->getElementStatus());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionElements()["ns2"]->getElementStatus());
        $this->assertEquals("FAILED", $transaction->getTransactionElements()["ns3"]->getElementStatus());


    }


    public function testOperationExceptionRaisedIfListGlueRecordsCalledWithNoneAccountDomain() {

        try {
            $this->api->domains()->glueRecordsList("bingobongo123.com");
            $this->fail("Should have thrown here");
        } catch (TransactionException $e) {
            $this->assertEquals(array("DOMAIN_NOT_IN_ACCOUNT"), array_keys($e->getTransactionErrors()));
        }

    }


    public function testGlueRecordsCorrectlyListedForAccountDomain() {

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));

        $this->assertEquals(array(), $this->api->domains()->glueRecordsList($domainName));


        $this->api->domains()->glueRecordsSet($domainName, array(new DomainNameGlueRecord("ns1", "88.32.3.44"), new DomainNameGlueRecord("ns2", "77.55.66.77")));

        $glueRecords = $this->api->domains()->glueRecordsList($domainName);

        $this->assertEquals(2, sizeof($glueRecords));
        $this->assertEquals(array("ns1", "ns2"), SerialisableArrayUtils::getMemberValueArrayForObjects("subDomainPrefix", $glueRecords));
        $this->assertEquals(array("88.32.3.44", "77.55.66.77"), SerialisableArrayUtils::getMemberValueArrayForObjects("ipv4Address", $glueRecords));


    }


    public function testTransactionProgressIsCorrectlyMonitoredWhenProgressKeyPassedToGlueRecordSetAndRemoveMethods() {

        $domainName = "gluetest-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($domainName), 1, $owner, array("me.com", "you.com")));

        $progressKey = $this->api->utility()->createBulkOperation();
        $this->api->domains()->glueRecordsSet($domainName, array(new DomainNameGlueRecord("ns1", "88.32.3.44"), new DomainNameGlueRecord("ns2", "77.55.66.77"), new DomainNameGlueRecord("ns3")), $progressKey);

        $bulkOperationProgress = $this->api->utility()->getBulkOperationProgress($progressKey);
        $this->assertEquals("COMPLETED", $bulkOperationProgress->getStatus());
        $this->assertEquals("SUCCEEDED", $bulkOperationProgress->getProgressItems()[0]->getStatus());
        $this->assertEquals("SUCCEEDED", $bulkOperationProgress->getProgressItems()[1]->getStatus());
        $this->assertEquals("FAILED", $bulkOperationProgress->getProgressItems()[2]->getStatus());


        $progressKey = $this->api->utility()->createBulkOperation();
        $this->api->domains()->glueRecordsRemove($domainName, array("ns1", "ns3", "ns2"), $progressKey);

        $bulkOperationProgress = $this->api->utility()->getBulkOperationProgress($progressKey);
        $this->assertEquals("COMPLETED", $bulkOperationProgress->getStatus());
        $this->assertEquals("SUCCEEDED", $bulkOperationProgress->getProgressItems()[0]->getStatus());
        $this->assertEquals("FAILED", $bulkOperationProgress->getProgressItems()[1]->getStatus());
        $this->assertEquals("SUCCEEDED", $bulkOperationProgress->getProgressItems()[2]->getStatus());

    }


    public function testOwnerContactChangesForGTLDDomainNamesAreSubmittedForVerificationAndMayBeCancelled() {

        $newBlogDomain = "ownerchange-" . date("U") . ".rodeo";
        $newBlogDomain2 = "nochange-" . date("U") . ".rodeo";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newBlogDomain, $newBlogDomain2), 1, $owner, array("ns1.oxil.uk", "ns2.oxil.uk")));

        // Now retrieve the rodeo domain
        $domains = $this->api->domains()->getMultiple(array($newBlogDomain));
        $domainName = $domains[$newBlogDomain];

        $this->assertEquals("LIVE", $domainName->getOwnerContact()->getStatus());
        $this->assertEquals("", $domainName->getOwnerContact()->getPendingContact());

        // Now make an owner contact change
        $newOwner = new DomainNameContact("Jeffrey Babes", "jeffrey@oxil.co.uk", "Bingo Land", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newBlogDomain), $newOwner));

        // Now retrieve the rodeo domain
        $domains = $this->api->domains()->getMultiple(array($newBlogDomain));
        $domainName = $domains[$newBlogDomain];

        $this->assertEquals("PENDING_CHANGES", $domainName->getOwnerContact()->getStatus());
        $this->assertEquals("Marky Babes", $domainName->getOwnerContact()->getName());
        $this->assertTrue($domainName->getOwnerContact()->getPendingContact() instanceof DomainNameContact);
        $this->assertEquals("Jeffrey Babes", $domainName->getOwnerContact()->getPendingContact()->getName());

        $transaction = $this->api->domains()->ownerChangeCancel(array($newBlogDomain, $newBlogDomain2, "anotherone.com"));

        $this->assertTrue($transaction instanceof Transaction);

        $this->assertEquals("PARTIALLY_SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(3, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();

        $this->assertEquals("SUCCEEDED"
            , $elements[$newBlogDomain]->getElementStatus());

        $this->assertEquals("FAILED", $elements[$newBlogDomain2]->getElementStatus());
        $this->assertTrue(isset($elements[$newBlogDomain2]->getElementErrors()["DOMAIN_OWNER_NOT_PENDING_CHANGES"]));

        $this->assertEquals("FAILED", $elements["anotherone.com"]->getElementStatus());
        $this->assertTrue(isset($elements["anotherone.com"]->getElementErrors()["DOMAIN_NOT_IN_ACCOUNT"]));


        // Now retrieve the rodeo domain
        $domains = $this->api->domains()->getMultiple(array($newBlogDomain));
        $domainName = $domains[$newBlogDomain];

        $this->assertEquals("LIVE", $domainName->getOwnerContact()->getStatus());
        $this->assertEquals("", $domainName->getOwnerContact()->getPendingContact());

    }

    public function testCanAddTagsToDomainNameOnCreateUpdateThemAndSeeThemInDomainListings() {

        $prefix = "validdomain-" . date("U");
        $newUKDomain = $prefix . ".uk";
        $newRodeoDomain = $prefix . ".rodeo";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain, $newRodeoDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, [], true, array("Film", "Television", "Media")));

        // Now grab the domains in question and check for existence of tags
        $domains = $this->api->domains()->getMultiple(array($newUKDomain, $newRodeoDomain));

        $this->assertEquals(array("Film", "Media", "Television"), $domains[$newUKDomain]->getTags());
        $this->assertEquals(array("Film", "Media", "Television"), $domains[$newRodeoDomain]->getTags());


        $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain), null, null, null, null, null, null, null, null, array("Fishing", "Philosophy"), null), null);
        $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newRodeoDomain), null, null, null, null, null, null, null, null, null, array("Television", "Media")), null);


        // Now grab the domains in question and check for tag updates
        $domains = $this->api->domains()->getMultiple(array($newUKDomain, $newRodeoDomain));

        $this->assertEquals(array("Film", "Fishing", "Media", "Philosophy", "Television"), $domains[$newUKDomain]->getTags());
        $this->assertEquals(array("Film"), $domains[$newRodeoDomain]->getTags());

        $this->api->domains()->update(new DomainNameUpdateDescriptor(array($newUKDomain, $newRodeoDomain), null, null, null, null, null, null, null, null, array("Cooking", "Shopping"), array("Film")), null);

        // Now grab the domains in question and check for tag updates
        $domains = $this->api->domains()->getMultiple(array($newUKDomain, $newRodeoDomain));

        $this->assertEquals(array("Cooking", "Fishing", "Media", "Philosophy", "Shopping", "Television"), $domains[$newUKDomain]->getTags());
        $this->assertEquals(array("Cooking", "Shopping"), $domains[$newRodeoDomain]->getTags());


        // Now get a list and confirm that tags are present
        $list = $this->api->domains()->list($prefix);

        $this->assertEquals(2, sizeof($list->getDomainNameSummaries()));
        $firstSummary = $list->getDomainNameSummaries()[0];
        $this->assertEquals($prefix . ".rodeo", $firstSummary->getDomainName());
        $this->assertEquals(array("Cooking", "Shopping"), $firstSummary->getTags());

        $secondSummary = $list->getDomainNameSummaries()[1];
        $this->assertEquals($prefix . ".uk", $secondSummary->getDomainName());
        $this->assertEquals(array("Cooking", "Fishing", "Media", "Philosophy", "Shopping", "Television"), $secondSummary->getTags());


    }


    public function testAttemptsToAddDNSSECRecordsForBadDomainsOrTLDsNotEnabledForDNSSECReturnFailedTransactionWithOperationErrors() {


        $transaction = $this->api->domains()->dnssecRecordsAdd("baddomain.com", [
        ]);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());

        $this->assertEquals("DOMAIN_NOT_IN_ACCOUNT", $transaction->getTransactionError());


        $prefix = "validdomain-" . date("U");
        $newUKDomain = $prefix . ".uk";
        $newRodeoDomain = $prefix . ".scot";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain, $newRodeoDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, true, array("Film", "Television", "Media")));
        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor([$newUKDomain], "EXPIRED"));


        $transaction = $this->api->domains()->dnssecRecordsAdd($newUKDomain, [
        ]);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());

        $this->assertEquals("DOMAIN_INVALID_FOR_DNSSEC", $transaction->getTransactionError());


        $transaction = $this->api->domains()->dnssecRecordsAdd($newRodeoDomain, [
        ]);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());

        $this->assertEquals("DOMAIN_UNSUPPORTED_FOR_DNSSEC", $transaction->getTransactionError());


    }

    public function testInvalidAndBadlyFormedDNSSecRecordsThrowExceptionsWhenAdded() {

        $blank = new DomainNameDNSSECRecord(null, null, null, null);
        $justKeyTag = new DomainNameDNSSECRecord(1234567);
        $badKeyTag = new DomainNameDNSSECRecord("ABCDE", "ABCDEF12345");
        $badDigest = new DomainNameDNSSECRecord(1234678, "ABCDEFGHIJKLM");
        $badAlgorithm = new DomainNameDNSSECRecord(1234789, "ABCDEF12345", 12);
        $badDigestType = new DomainNameDNSSECRecord(1234890, "ABCDEF12345", 5, 12);

        $transaction = $this->api->domains()->dnssecRecordsAdd("max63sagreatbigridiculouslylongdomainnamebutthatiswhatineed1234.xyz", [
            $blank, $justKeyTag, $badKeyTag, $badDigest, $badAlgorithm, $badDigestType
        ]);


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $this->assertEquals(6, sizeof($transaction->getTransactionElements()));


        $element = $transaction->getTransactionElements()[""];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals($blank->__toArray(), $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_MISSING_KEY_TAG", "DNSSEC_RECORD_MISSING_DIGEST", "DNSSEC_RECORD_MISSING_ALGORITHM", "DNSSEC_RECORD_MISSING_DIGEST_TYPE"), array_keys($element->getElementErrors()));

        $element = $transaction->getTransactionElements()[1234567];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals($justKeyTag->__toArray(), $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_MISSING_DIGEST"), array_keys($element->getElementErrors()));

        $element = $transaction->getTransactionElements()["ABCDE"];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals($badKeyTag->__toArray(), $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_INVALID_KEY_TAG"), array_keys($element->getElementErrors()));

        $element = $transaction->getTransactionElements()[1234678];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals($badDigest->__toArray(), $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_INVALID_DIGEST"), array_keys($element->getElementErrors()));

        $element = $transaction->getTransactionElements()[1234789];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals($badAlgorithm->__toArray(), $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_INVALID_ALGORITHM"), array_keys($element->getElementErrors()));

        $element = $transaction->getTransactionElements()[1234890];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals($badDigestType->__toArray(), $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_INVALID_DIGEST_TYPE"), array_keys($element->getElementErrors()));

    }


    public function testUnsupportedAlgorithmExceptionRaisedIfNotSupportedByRegistry() {

        $prefix = "validdomain-" . date("U");
        $comDomain = $prefix . ".com";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($comDomain), 1, $owner, array("ns1.netistrar.xyz", "ns2.netistrar.xyz"), null, null, null, 2, true, array("Film", "Television", "Media")));

        $unsupported = new DomainNameDNSSECRecord(12345, "ABCDEF12345567", 3, 1);

        $transaction = $this->api->domains()->dnssecRecordsAdd($comDomain, [
            $unsupported
        ]);


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());

        $this->assertEquals("DOMAIN_UNSUPPORTED_PARAMETER_FOR_DNSSEC", $transaction->getTransactionError());


    }


    public function testCanAddAndListValidDNSSECRecordsProvidedDomainExistsInAccount() {

        $newDomain = "validdomain-" . date("U") . ".xyz";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, true, array("Film", "Television", "Media")));


        $dnsSecRecord1 = new DomainNameDNSSECRecord("12345", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AC");
        $dnsSecRecord2 = new DomainNameDNSSECRecord("12346", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AD");
        $dnsSecRecord3 = new DomainNameDNSSECRecord("12347", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AE");


        $transaction = $this->api->domains()->dnssecRecordsAdd($newDomain, [
            $dnsSecRecord1, $dnsSecRecord2, $dnsSecRecord3
        ]);


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        try {
            $this->api->domains()->dnssecRecordsList("idontexist.xyz");
            $this->fail("Should have thrown here");
        } catch (TransactionException $e) {
            // Success
        }

        $list = $this->api->domains()->dnssecRecordsList($newDomain);
        $this->assertEquals(12345, $list[0]->getKeyTag());
        $this->assertEquals(12346, $list[1]->getKeyTag());
        $this->assertEquals(12347, $list[2]->getKeyTag());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AC", $list[0]->getDigest());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AD", $list[1]->getDigest());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AE", $list[2]->getDigest());

        $dnsSecRecord4 = new DomainNameDNSSECRecord("12348", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4A1");
        $transaction = $this->api->domains()->dnssecRecordsAdd($newDomain, [
            $dnsSecRecord4
        ]);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());

        $list = $this->api->domains()->dnssecRecordsList($newDomain);
        $this->assertEquals(4, sizeof($list));
        $this->assertEquals(12345, $list[0]->getKeyTag());
        $this->assertEquals(12346, $list[1]->getKeyTag());
        $this->assertEquals(12347, $list[2]->getKeyTag());
        $this->assertEquals(12348, $list[3]->getKeyTag());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AC", $list[0]->getDigest());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AD", $list[1]->getDigest());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AE", $list[2]->getDigest());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4A1", $list[3]->getDigest());


    }


    public function testCannotAddAlreadyExistingDNSSECRecord() {

        $newDomain = "validdomain-" . date("U") . ".xyz";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, true, array("Film", "Television", "Media")));


        $dnsSecRecord1 = new DomainNameDNSSECRecord("12345", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AC");
        $dnsSecRecord2 = new DomainNameDNSSECRecord("12346", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AD");
        $dnsSecRecord3 = new DomainNameDNSSECRecord("12347", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AE");


        $transaction = $this->api->domains()->dnssecRecordsAdd($newDomain, [
            $dnsSecRecord1, $dnsSecRecord2, $dnsSecRecord3
        ]);


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        $dnsSecRecord1->setDigest("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4A1");
        $transaction = $this->api->domains()->dnssecRecordsAdd($newDomain, [
            $dnsSecRecord1
        ]);


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_SET", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));

        $element = $transaction->getTransactionElements()["12345"];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals($dnsSecRecord1->__toArray(), $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_ALREADY_EXISTS"), array_keys($element->getElementErrors()));


    }


    public function testCannotRemoveDNSSECRecordsForBadOrInactiveDomainsOrMissingRecords() {


        $transaction = $this->api->domains()->dnssecRecordsRemove("baddomain.com", [
            "12345"
        ]);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_REMOVE", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());

        $this->assertEquals("DOMAIN_NOT_IN_ACCOUNT", $transaction->getTransactionError());


        $prefix = "validdomain-" . date("U");
        $newUKDomain = $prefix . ".uk";
        $newRodeoDomain = $prefix . ".rodeo";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain, $newRodeoDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, true, array("Film", "Television", "Media")));
        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor([$newUKDomain], "EXPIRED"));


        $transaction = $this->api->domains()->dnssecRecordsRemove($newUKDomain, [
            "12345"
        ]);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_REMOVE", $transaction->getTransactionType());
        $this->assertEquals("FAILED", $transaction->getTransactionStatus());

        $this->assertEquals("DOMAIN_INVALID_FOR_DNSSEC", $transaction->getTransactionError());


        $transaction = $this->api->domains()->dnssecRecordsRemove("max63sagreatbigridiculouslylongdomainnamebutthatiswhatineed1234.xyz", [
            13579
        ]);

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_DNSSEC_RECORD_REMOVE", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));

        $element = $transaction->getTransactionElements()[13579];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(13579, $element->getOperationData());
        $this->assertEquals(array("DNSSEC_RECORD_UNKNOWN"), array_keys($element->getElementErrors()));


    }


    public function testCanRemoveValidlyAddedDNSSECRecords() {

        $newDomain = "validdomain-" . date("U") . ".xyz";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, true, array("Film", "Television", "Media")));

        $dnsSecRecord1 = new DomainNameDNSSECRecord("12345", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AC");
        $dnsSecRecord2 = new DomainNameDNSSECRecord("12346", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AD");
        $dnsSecRecord3 = new DomainNameDNSSECRecord("12347", "49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AE");

        $this->api->domains()->dnssecRecordsAdd($newDomain, [
            $dnsSecRecord1, $dnsSecRecord2, $dnsSecRecord3
        ]);

        $list = $this->api->domains()->dnssecRecordsList($newDomain);
        $this->assertEquals(3, sizeof($list));

        $this->api->domains()->dnssecRecordsRemove($newDomain, [12346]);

        $list = $this->api->domains()->dnssecRecordsList($newDomain);
        $this->assertEquals(2, sizeof($list));
        $this->assertEquals(12345, $list[0]->getKeyTag());
        $this->assertEquals(12347, $list[1]->getKeyTag());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AC", $list[0]->getDigest());
        $this->assertEquals("49FD46E6C4B45C55D4AC49FD46E6C4B45C55D4AE", $list[1]->getDigest());


        // Check disable as well
        $this->api->domains()->dnssecDisable($newDomain);

        $list = $this->api->domains()->dnssecRecordsList($newDomain);
        $this->assertEquals(0, sizeof($list));
    }


    public function testCanRegisterIDNsInCOManNET() {

        $idnDomain = idn_to_ascii("點看" . date("U"), 0, INTL_IDNA_VARIANT_UTS46);

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($idnDomain . ".com", $idnDomain . ".net"), 1, $owner, array("ns1.netistrar.org", "ns2.netistrar.org")));

        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        // Confirm normal domain still succeeds.
        $normalDomain = "normal-" . date("U");

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($normalDomain . ".com", $normalDomain . ".net"), 1, $owner, array("ns1.netistrar.org", "ns2.netistrar.org")));

        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


    }


    public function testCanRegisterScotWithIntendedUseExtension() {

        $scotDomain = "validdomain-" . date("U") . ".scot";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($scotDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, true, array("Film", "Television", "Media")));

        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        $domain = $this->api->domains()->get($scotDomain);
        $this->assertEquals("ACTIVE", $domain->getStatus());
        $this->assertNotNull($domain->getExpiryDate());


    }


    public function testAttemptsToRestoreDomainsNotInAccountOrRGPReturnFailedTransaction() {


        // Restore a bad domain
        $transaction = $this->api->domains()->restore("not-existent.com", "65646463", 1);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element = $transaction->getTransactionElements()["not-existent.com"];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array("DOMAIN_NOT_IN_ACCOUNT"), array_keys($element->getElementErrors()));


        // Restore a bad domain
        $transaction = $this->api->domains()->restore("max63sagreatbigridiculouslylongdomainnamebutthatiswhatineed1234.xyz", "65646463", 1);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element = $transaction->getTransactionElements()["max63sagreatbigridiculouslylongdomainnamebutthatiswhatineed1234.xyz"];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array("DOMAIN_INVALID_FOR_RESTORE"), array_keys($element->getElementErrors()));


    }


    public function testAttemptsToRestoreValidRGPDomainAndAddTooManyRegistrationYearsReturnsFailedTransaction() {


        // Create an RGP domain
        $domainName = $this->api->test()->createRGPDomain();

        // Restore a bad domain
        $transaction = $this->api->domains()->restore($domainName, "65646463", 10);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element = $transaction->getTransactionElements()[$domainName];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array("DOMAIN_TOO_MANY_REGISTRATION_YEARS"), array_keys($element->getElementErrors()));


    }


    public function testAttemptsToRestoreValidRGPDomainWithInvalidRestoreCodeReturnsFailedTransaction() {

        // Create an RGP domain
        $domainName = $this->api->test()->createRGPDomain();

        // Restore a bad domain
        $transaction = $this->api->domains()->restore($domainName, "65646463", 3);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element = $transaction->getTransactionElements()[$domainName];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array("RESTORE_INVALID_CODE"), array_keys($element->getElementErrors()));


    }


    public function testAttemptsToRestoreRGPDomainWithNoRGPProductReturnsFailedTransaction() {

        $newDomain = "validdomain-" . date("U") . ".xyz";
        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, [], true, array("Film", "Television", "Media")));

        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor([$newDomain], "RGP"));

        // Restore a bad domain
        $transaction = $this->api->domains()->restore($newDomain, "65646463", 3);

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element = $transaction->getTransactionElements()[$newDomain];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array("RESTORE_MANUAL_ONLY"), array_keys($element->getElementErrors()));


    }


    public function testCanRestoreValidDomainsFromRGP() {

        // Create an RGP domain
        $domainName = $this->api->test()->createRGPDomain();

        $liveAvailability = $this->api->domains()->liveAvailability($domainName);
        $initialExpiry = date_create_from_format("d/m/Y", $liveAvailability->getAdditionalData()["expiryDate"]);

        $transaction = $this->api->domains()->restore($domainName, $liveAvailability->getAdditionalData()["restoreCode"], 3);

        // Check transaction succeeded
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());

        // Check amounts taken correct
        $this->assertEquals(275.36, $transaction->getOrderSubtotal());
        $this->assertEquals(55.07, $transaction->getOrderTaxes());
        $this->assertEquals(330.43, $transaction->getOrderTotal());

        // Check status and expiry now updated on domain name
        $reDomain = $this->api->domains()->get($domainName);
        $this->assertEquals("ACTIVE", $reDomain->getStatus());
        $currentExpiry = date_create_from_format("d/m/Y H:i:s", $reDomain->getExpiryDate());
        $currentExpiry->sub(new DateInterval("P2Y"));
        $this->assertEquals($initialExpiry->format("d/m/Y"), $currentExpiry->format("d/m/Y"));

    }


    public function testUnconfiguredTransactionErrorReturnedIfAttemptToBuyDomainWithNoProductsDefined() {

        $unconfiguredDomain = "hello12345.boutique";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($unconfiguredDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1));

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element = $transaction->getTransactionElements()["hello12345.boutique"];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array("DOMAIN_UNCONFIGURED_FOR_SALE"), array_keys($element->getElementErrors()));


    }

    public function testManualPremiumTransactionErrorReturnedIfAttemptToBuyDomainWithNoProductsDefined() {

        $unconfiguredDomain = "fine.website";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($unconfiguredDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1));

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element = $transaction->getTransactionElements()["fine.website"];
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array("DOMAIN_MANUAL_PREMIUM"), array_keys($element->getElementErrors()));


    }

    public function testCannotCreatePremiumDomainsIfMissingOrInvalidRegistrationCodes() {

        $chargeDomain = "industry.academy";
        // $priceDomain = "wildmind.biz";
        $feeDomain = "weather.xyz";


        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($chargeDomain, $feeDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1));

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $chargeElement = $transaction->getTransactionElements()["industry.academy"];
        $this->assertEquals("FAILED", $chargeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_MISSING_PREMIUM_REGISTRATION_CODE"], array_keys($chargeElement->getElementErrors()));

        $feeElement = $transaction->getTransactionElements()["weather.xyz"];
        $this->assertEquals("FAILED", $feeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_MISSING_PREMIUM_REGISTRATION_CODE"], array_keys($feeElement->getElementErrors()));


        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($chargeDomain, $feeDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1, [],
            ["industry.academy" => "BADCODE1", "weather.xyz" => "BADCODE2"]));

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $chargeElement = $transaction->getTransactionElements()["industry.academy"];
        $this->assertEquals("FAILED", $chargeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_INVALID_PREMIUM_REGISTRATION_CODE"], array_keys($chargeElement->getElementErrors()));

        $feeElement = $transaction->getTransactionElements()["weather.xyz"];
        $this->assertEquals("FAILED", $feeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_INVALID_PREMIUM_REGISTRATION_CODE"], array_keys($feeElement->getElementErrors()));


    }


    public function testCanRegisterPremiumDomainsWhereValidRegistrationCodesAreSupplied() {

        $chargeDomain = "industry.academy";
        // $priceDomain = "wildmind.biz";
        $feeDomain = "weather.xyz";

        $chargeCode = $this->api->domains()->liveAvailability("industry.academy")->getAdditionalData()["premiumRegistrationCode"];
        $feeCode = $this->api->domains()->liveAvailability("weather.xyz")->getAdditionalData()["premiumRegistrationCode"];

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($chargeDomain, $feeDomain), 2, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1, [],
            ["industry.academy" => $chargeCode, "weather.xyz" => $feeCode]));


        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        $domainName = $this->api->domains()->get($chargeDomain);
        $this->assertEquals("ACTIVE", $domainName->getStatus());
        $this->assertEquals((new DateTime())->add(new DateInterval("P2Y"))->format("d/m/Y"), substr($domainName->getExpiryDate(), 0, 10));

        $domainName = $this->api->domains()->get($feeDomain);
        $this->assertEquals("ACTIVE", $domainName->getStatus());
        $this->assertEquals((new DateTime())->add(new DateInterval("P2Y"))->format("d/m/Y"), substr($domainName->getExpiryDate(), 0, 10));


        $this->api->test()->deleteDomain("industry.academy");
        $this->api->test()->deleteDomain("weather.xyz");


    }


    public function testCannotRenewPremiumDomainsIfMissingOrInvalidRegistrationCodes() {

        $chargeDomain = "dry.academy";
        // $priceDomain = "wildmind.biz";
        $feeDomain = "purple.xyz";


        $chargeCode = $this->api->domains()->liveAvailability($chargeDomain)->getAdditionalData()["premiumRegistrationCode"];
        $feeCode = $this->api->domains()->liveAvailability($feeDomain)->getAdditionalData()["premiumRegistrationCode"];

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($chargeDomain, $feeDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1, [],
            [$chargeDomain => $chargeCode, $feeDomain => $feeCode]));
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());

        $transaction = $this->api->domains()->renewMultiple(new DomainNameRenewDescriptor([$chargeDomain, $feeDomain], 1));

        $chargeElement = $transaction->getTransactionElements()[$chargeDomain];
        $this->assertEquals("FAILED", $chargeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_MISSING_PREMIUM_RENEWAL_CODE"], array_keys($chargeElement->getElementErrors()));

        $feeElement = $transaction->getTransactionElements()[$feeDomain];
        $this->assertEquals("FAILED", $feeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_MISSING_PREMIUM_RENEWAL_CODE"], array_keys($feeElement->getElementErrors()));


        $transaction = $this->api->domains()->renewMultiple(new DomainNameRenewDescriptor([$chargeDomain, $feeDomain], 1, [$chargeDomain => "BADCODE1", $feeDomain => "BADCODE2"]));
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $chargeElement = $transaction->getTransactionElements()[$chargeDomain];
        $this->assertEquals("FAILED", $chargeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_INVALID_PREMIUM_RENEWAL_CODE"], array_keys($chargeElement->getElementErrors()));

        $feeElement = $transaction->getTransactionElements()[$feeDomain];
        $this->assertEquals("FAILED", $feeElement->getElementStatus());
        $this->assertEquals(["DOMAIN_INVALID_PREMIUM_RENEWAL_CODE"], array_keys($feeElement->getElementErrors()));


        $this->api->test()->deleteDomain($chargeDomain);
        $this->api->test()->deleteDomain($feeDomain);


    }


    public function testCanRenewPremiumDomainsWhereValidRegistrationCodesAreSupplied() {

        $chargeDomain = "green.academy";
        // $priceDomain = "wildmind.biz";
        $feeDomain = "brown.xyz";

        $chargeCode = $this->api->domains()->liveAvailability($chargeDomain)->getAdditionalData()["premiumRegistrationCode"];
        $feeCode = $this->api->domains()->liveAvailability($feeDomain)->getAdditionalData()["premiumRegistrationCode"];


        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($chargeDomain, $feeDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, null, 1, [],
            [$chargeDomain => $chargeCode, $feeDomain => $feeCode]));

        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());

        $chargeCode = $this->api->domains()->liveAvailability($chargeDomain)->getAdditionalData()["premiumRenewalCode"];
        $feeCode = $this->api->domains()->liveAvailability($feeDomain)->getAdditionalData()["premiumRenewalCode"];


//        // Renew for 2 years
        $transaction = $this->api->domains()->renew($chargeDomain, 2, $chargeCode);
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());

        $domainName = $this->api->domains()->get($chargeDomain);
        $this->assertEquals("ACTIVE", $domainName->getStatus());
        $this->assertEquals((new DateTime())->add(new DateInterval("P3Y"))->format("d/m/Y"), substr($domainName->getExpiryDate(), 0, 10));


        $transaction = $this->api->domains()->renewMultiple(new DomainNameRenewDescriptor([$feeDomain], 3, [$feeDomain => $feeCode]));
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        $domainName = $this->api->domains()->get($feeDomain);
        $this->assertEquals("ACTIVE", $domainName->getStatus());
        $this->assertEquals((new DateTime())->add(new DateInterval("P4Y"))->format("d/m/Y"), substr($domainName->getExpiryDate(), 0, 10));


        $this->api->test()->deleteDomain($chargeDomain);
        $this->api->test()->deleteDomain($feeDomain);


    }




}
