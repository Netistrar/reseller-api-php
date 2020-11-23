<?php

namespace Netistrar\ClientAPI\Controllers;

use DateInterval;
use DateTime;
use Netistrar\ClientAPI\Exception\TransactionException;
use Netistrar\ClientAPI\Objects\Account\AccountNotification;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameCreateDescriptor;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameRenewDescriptor;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameTransferDescriptor;
use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameUpdateDescriptor;
use Netistrar\ClientAPI\Objects\Domain\DomainNameContact;
use Netistrar\ClientAPI\Objects\Test\Domain\TestDomainNameUpdateDescriptor;
use Netistrar\ClientAPI\Objects\Transaction\Transaction;

include_once "ClientAPITestBase.php";


class domainsTransferTest extends \ClientAPITestBase {

    public function testCanValidateTransferInForPullAndPushTransfers() {


        $newDomains = $this->api->test()->createPullTransferRodeoDomains(2);

        $rodeoTestDomain = $newDomains[0][0];
        $rodeoTestAuth = $newDomains[0][1];

        $rodeoTestDomain2 = $newDomains[1][0];
        $rodeoTestAuth2 = $newDomains[1][1];


        $domainContact = new DomainNameContact();

        // Try simple not registered domains and with invalid owner contact.
        $validationErrors = $this->api->domains()->transferValidate(new DomainNameTransferDescriptor(array("notregistered12345.rodeo", "shopping.rodeo", "ganymede-netistrar.co.uk", "notregistered12345.uk"),
            $domainContact));

        $this->assertEquals(4, sizeof($validationErrors));

        $this->assertEquals(array(
            "DOMAIN_INVALID_OWNER_CONTACT", "TRANSFER_DOMAIN_NOT_REGISTERED"), array_keys($validationErrors["notregistered12345.rodeo"]));


        $this->assertEquals(array("CONTACT_MISSING_NAME",
            "CONTACT_MISSING_EMAIL",
            "CONTACT_MISSING_STREET_1",
            "CONTACT_MISSING_CITY",
            "CONTACT_MISSING_COUNTY",
            "CONTACT_MISSING_POSTCODE"), array_keys($validationErrors["notregistered12345.rodeo"]["DOMAIN_INVALID_OWNER_CONTACT"]->getRelatedErrors()));


        $this->assertEquals(array(
            "DOMAIN_INVALID_OWNER_CONTACT", "TRANSFER_DOMAIN_NOT_REGISTERED"), array_keys($validationErrors["shopping.rodeo"]));


        $this->assertEquals(array("CONTACT_MISSING_NAME",
            "CONTACT_MISSING_EMAIL",
            "CONTACT_MISSING_STREET_1",
            "CONTACT_MISSING_CITY",
            "CONTACT_MISSING_COUNTY",
            "CONTACT_MISSING_POSTCODE"), array_keys($validationErrors["shopping.rodeo"]["DOMAIN_INVALID_OWNER_CONTACT"]->getRelatedErrors()));

        $this->assertEquals(array(
            "DOMAIN_INVALID_OWNER_CONTACT", "TRANSFER_DOMAIN_NOT_REGISTERED"), array_keys($validationErrors["notregistered12345.uk"]));

        $this->assertEquals(array("CONTACT_MISSING_NAME",
            "CONTACT_MISSING_EMAIL",
            "CONTACT_MISSING_STREET_1",
            "CONTACT_MISSING_CITY",
            "CONTACT_MISSING_COUNTY",
            "CONTACT_MISSING_POSTCODE",
            "CONTACT_MISSING_NOMINETREGISTRANTTYPE"), array_keys($validationErrors["notregistered12345.uk"]["DOMAIN_INVALID_OWNER_CONTACT"]->getRelatedErrors()));


        $validContact = new DomainNameContact("Test", "test@test.com", "myorg", "hello street", "hello road", "hello", "helloshire", "he12 144", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        // Test for not assigned pull domains.
        $validationErrors = $this->api->domains()->transferValidate(new DomainNameTransferDescriptor(array("ganymede-netistrar_.co.uk", "ganymede-2030media.co.uk"), $validContact));

        $this->assertEquals(2, sizeof($validationErrors));


        $this->assertNotNull($validationErrors["ganymede-netistrar_.co.uk"]["TRANSFER_DOMAIN_NOT_ASSIGNED"]);
        $this->assertNotNull($validationErrors["ganymede-2030media.co.uk"]["TRANSFER_DOMAIN_NOT_REGISTERED"]);


        // Test for an missing auth code first.
        $validationErrors = $this->api->domains()->transferValidate(new DomainNameTransferDescriptor(array($rodeoTestDomain), $validContact));
        $this->assertEquals(1, sizeof($validationErrors));
        $this->assertEquals(array("TRANSFER_DOMAIN_MISSING_AUTHCODE"), array_keys($validationErrors[$rodeoTestDomain]));


        // Test for an invalid auth code.
        $validationErrors = $this->api->domains()->transferValidate(new DomainNameTransferDescriptor(array($rodeoTestDomain . ",piggywinkle"), $validContact));
        $this->assertEquals(1, sizeof($validationErrors));
        $this->assertEquals(array("TRANSFER_DOMAIN_INVALID_AUTHCODE"), array_keys($validationErrors[$rodeoTestDomain]));


        // Test for a ready one.
        $validationErrors = $this->api->domains()->transferValidate(new DomainNameTransferDescriptor(array($rodeoTestDomain . "," . $rodeoTestAuth), $validContact));
        $this->assertEquals(0, sizeof($validationErrors));


    }


    public function testFailedElementsAreReturnedIfValidationFailsOnCreateTransfer() {


        $validContact = new DomainNameContact("Test", "test@test.com", "testorg", "hello street", "hello road", "hello", "helloshire", "he12 144", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        // Try simple not registered domains.
        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array("notregistered12345.rodeo", "shopping.rodeo", "notregistered12345.uk"), $validContact));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $transactionElements = $transaction->getTransactionElements();
        $firstElement = $transactionElements["notregistered12345.rodeo"];
        $this->assertEquals("FAILED", $firstElement->getElementStatus());


    }


    public function testOperationExceptionRaisedIfTransferCannotBeStartedAtServerSide() {


        $comTestDomain = "transfer-" . date("U") . ".com";

        $validContact = new DomainNameContact("Test", "test@test.com", "testorg", "hello street", "hello road", "hello", "helloshire", "he12 144", "GB", null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        // Create and read the domain
        $this->api->domains()->create(new DomainNameCreateDescriptor(array($comTestDomain), 1, $validContact, array("ns1.test.uk", "ns2.test.uk")));
        $this->api->test()->updateDomains(new TestDomainNameUpdateDescriptor(array($comTestDomain), null, null, null, false));
        $readDomain = $this->api->domains()->get($comTestDomain);

        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($comTestDomain . "," . $readDomain->getAuthCode()), $validContact));

        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $transactionElements = $transaction->getTransactionElements();

    }


    public function testCanCreatePullTransferInDomainsWhenValidationSucceedsAndTheseAreProcessedAsOrdersCorrectly() {

        $newDomains = $this->api->test()->createPullTransferRodeoDomains(2);

        $rodeoTestDomain = $newDomains[0][0];
        $rodeoTestAuth = $newDomains[0][1];
        $rodeoTestIdentifier = $rodeoTestDomain . "," . $rodeoTestAuth;

        $rodeoTestDomain2 = $newDomains[1][0];
        $rodeoTestAuth2 = $newDomains[1][1];
        $rodeoTestIdentifier2 = $rodeoTestDomain2 . "," . $rodeoTestAuth2;

        $validContact = new DomainNameContact("Test", "test@test.com", "", "hello street", "hello road", "hello", "helloshire", "OX4 7ED", "GB", "", "", "", "", "", "", array(), array("nominetRegistrantType" => "IND"));

        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($rodeoTestIdentifier, $rodeoTestIdentifier2), $validContact, null, null, null, 2, true));

        // Add extra values we now expect
        $validContact->__setSerialisablePropertyMap(array("status" => "LIVE", "pendingContact" => ""));

        $this->assertTrue($transaction instanceof Transaction);
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_TRANSFER_IN_CREATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getOrderId());
        $this->assertEquals("GBP", $transaction->getOrderCurrency());
        $this->assertEquals(110.24, $transaction->getOrderSubtotal());
        $this->assertEquals(22.05, $transaction->getOrderTaxes());
        $this->assertEquals(132.29, $transaction->getOrderTotal());

        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();

        $element = $elements[$rodeoTestDomain];
        $this->assertEquals($rodeoTestDomain, $element->getDescription());
        $this->assertEquals("SUCCEEDED", $element->getElementStatus());
        $this->assertEquals(55.12, $element->getOrderLineSubtotal());
        $this->assertEquals(11.02, $element->getOrderLineTaxes());
        $this->assertEquals(66.14, $element->getOrderLineTotal());

        $element = $elements[$rodeoTestDomain2];
        $this->assertEquals($rodeoTestDomain2, $element->getDescription());
        $this->assertEquals("SUCCEEDED", $element->getElementStatus());
        $this->assertEquals(55.12, $element->getOrderLineSubtotal());
        $this->assertEquals(11.02, $element->getOrderLineTaxes());
        $this->assertEquals(66.14, $element->getOrderLineTotal());

        $checkDomains = $this->api->domains()->getMultiple(array($rodeoTestDomain, $rodeoTestDomain2));

        $domain1 = $checkDomains[$rodeoTestDomain];
        $this->assertEquals("TRANSFER_IN_AWAITING_RESPONSE", $domain1->getStatus());
        $this->assertEquals($validContact, $domain1->getOwnerContact());
        $this->assertEquals("Oxford Information Labs", $domain1->getAdminContact()->getName());
        $this->assertEquals("Oxford Information Labs", $domain1->getBillingContact()->getName());
        $this->assertEquals("Oxford Information Labs", $domain1->getTechnicalContact()->getName());
        $this->assertEquals(0, $domain1->getPrivacyProxy());
        $this->assertEquals(1, $domain1->getAutoRenew());


        $domain2 = $checkDomains[$rodeoTestDomain2];
        $this->assertEquals("TRANSFER_IN_AWAITING_RESPONSE", $domain2->getStatus());
        $this->assertEquals($validContact, $domain2->getOwnerContact());
        $this->assertEquals("Oxford Information Labs", $domain2->getAdminContact()->getName());
        $this->assertEquals("Oxford Information Labs", $domain2->getBillingContact()->getName());
        $this->assertEquals("Oxford Information Labs", $domain2->getTechnicalContact()->getName());
        $this->assertEquals(0, $domain2->getPrivacyProxy());
        $this->assertEquals(1, $domain2->getAutoRenew());


        // Now complete one of the transfers
        $this->api->test()->approveIncomingTransferOtherRegistrar([$rodeoTestDomain]);

        $domain = $this->api->domains()->get($rodeoTestDomain);
        $this->assertNotNull($domain->getExpiryDate());
        $this->assertEquals("ACTIVE", $domain->getStatus());
        $this->assertEquals(3, sizeof($domain->getNameservers()));
        $this->assertEquals("ns1.monkey.com", $domain->getNameservers()[0]);
        $this->assertEquals("ns2.monkey.com", $domain->getNameservers()[1]);
        $this->assertEquals("ns1." . $rodeoTestDomain, $domain->getNameservers()[2]);

        // Check DNSSEC Records were imported correctly
        $dnsSec = $this->api->domains()->dnssecRecordsList($rodeoTestDomain);
        $this->assertEquals(2, sizeof($dnsSec));
        $this->assertEquals(12345, $dnsSec[0]->getKeyTag());
        $this->assertEquals(12346, $dnsSec[1]->getKeyTag());

        // Check Glue records were imported correctly
        $glueRecords = $this->api->domains()->glueRecordsList($rodeoTestDomain);
        $this->assertEquals(2, sizeof($glueRecords));
        $this->assertEquals("ns1", $glueRecords[0]->getSubDomainPrefix());
        $this->assertEquals("8.8.8.8", $glueRecords[0]->getIpv4Address());
        $this->assertEquals("ns2", $glueRecords[1]->getSubDomainPrefix());
        $this->assertEquals("8.8.8.8", $glueRecords[1]->getIpv4Address());

    }


    public function testCanCreatePushTransferInDomainsWhenValidationSucceedsAndTheseAreProcessedAsOrdersCorrectly() {

        $pushTransferDomains = $this->api->test()->createPushTransferUKDomains(1);

        $validContact = new DomainNameContact("Owner", "owner@dontreset.com", "Pinky Inc", "pinky strasse", "pink road", "pinkish", "pinkshire", "pe33 7YY", "GB", null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($pushTransferDomains[0]), $validContact, null, null, null, 2));


        $this->assertTrue($transaction instanceof Transaction);

        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_TRANSFER_IN_CREATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getOrderId());
        $this->assertEquals("GBP", $transaction->getOrderCurrency());
        $this->assertEquals(0, $transaction->getOrderSubtotal());
        $this->assertEquals(0, $transaction->getOrderTaxes());
        $this->assertEquals(0, $transaction->getOrderTotal());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();

        $element = $elements[$pushTransferDomains[0]];
        $this->assertEquals($pushTransferDomains[0], $element->getDescription());
        $this->assertEquals("SUCCEEDED", $element->getElementStatus());
        $this->assertEquals(0, $element->getOrderLineSubtotal());
        $this->assertEquals(0, $element->getOrderLineTaxes());
        $this->assertEquals(0, $element->getOrderLineTotal());


        // Accept the ownership confirmation
        $this->api->test()->acceptOwnershipConfirmation($pushTransferDomains);

        // Confirm that domain is now active
        $checkDomains = $this->api->domains()->getMultiple(array($pushTransferDomains[0]));
        $reDomain = $checkDomains[$pushTransferDomains[0]];
        $this->assertEquals("ACTIVE", $reDomain->getStatus());
        $this->assertNotNull($reDomain->getExpiryDate());
        $this->assertEquals(0, $reDomain->getPrivacyProxy());
        $this->assertEquals(3, sizeof($reDomain->getNameservers()));
        $this->assertContains("ns1.monkey.com", $reDomain->getNameservers());
        $this->assertContains("ns2.monkey.com", $reDomain->getNameservers());
        $this->assertContains("ns1." . $pushTransferDomains[0], $reDomain->getNameservers());


        // Check Glue records were imported correctly
        $glueRecords = $this->api->domains()->glueRecordsList($pushTransferDomains[0]);
        $this->assertEquals(2, sizeof($glueRecords));
        $this->assertEquals("8.8.8.8", $glueRecords[0]->getIpv4Address());
        $this->assertEquals("8.8.8.8", $glueRecords[1]->getIpv4Address());


    }


    public function testCannotCancelTransfersForDomainsNotInTransferStatus() {

        $rodeoTestDomain = "transfer-" . date("U") . ".rodeo";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($rodeoTestDomain), 1, $owner, array("ns1.oxil.com", "ns2.oxil.com")));

        $transaction = $this->api->domains()->transferCancel(array($rodeoTestDomain, "thisdoesnotexist123.com"));
        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_TRANSFER_IN_CANCEL", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));


        $this->assertTrue(isset($transaction->getTransactionElements()["thisdoesnotexist123.com"]->getElementErrors()["DOMAIN_NOT_IN_ACCOUNT"]));
        $this->assertTrue(isset($transaction->getTransactionElements()[$rodeoTestDomain]->getElementErrors()["DOMAIN_TRANSFER_NOT_CANCELLABLE"]));


    }


    public function testGenericTransferExceptionReturnedIfOtherIssueWhenCancellingDomainTransferAndNoRefundIssued() {


        $domains = $this->api->test()->createPullTransferRodeoDomains(1);
        $originalBalance = $this->api->account()->balance();

        $rodeoTestDomain = $domains[0][0];
        $authCode = $domains[0][1];

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Hello Inc", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");


        // Create the transfers
        $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($rodeoTestDomain . "," . $authCode), $owner));

        $this->assertEquals($originalBalance - 66.14, $this->api->account()->balance());

        // Now manually accept the transfer to simulate a none-detected issue.
        $this->api->test()->approveIncomingTransferOtherRegistrar(array($rodeoTestDomain));

        $transaction = $this->api->domains()->transferCancel(array($rodeoTestDomain));
        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_TRANSFER_IN_CANCEL", $transaction->getTransactionType());
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $this->assertTrue(isset($transaction->getTransactionElements()[$rodeoTestDomain]->getElementErrors()["DOMAIN_TRANSFER_NOT_CANCELLABLE"]));


    }


    public function testCanCancelTransferAndGetRefundIfAwaitingConfirmation() {

        $domains = $this->api->test()->createPullTransferRodeoDomains(1);
        $originalBalance = $this->api->account()->balance();

        $rodeoTestDomain = $domains[0][0];
        $authCode = $domains[0][1];

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Hello Inc", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");


        // Create the transfers
        $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($rodeoTestDomain . "," . $authCode), $owner));

        $this->assertEquals($originalBalance - 66.14, $this->api->account()->balance());

        // Cancel the transfer
        $transaction = $this->api->domains()->transferCancel(array($rodeoTestDomain));


        $this->assertTrue($transaction instanceof Transaction);
        $this->assertEquals("DOMAIN_TRANSFER_IN_CANCEL", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $this->assertEquals(-55.12, $transaction->getOrderSubtotal());
        $this->assertEquals(-11.02, $transaction->getOrderTaxes());
        $this->assertEquals(-66.14, $transaction->getOrderTotal());

        $element1 = $transaction->getTransactionElements()[$rodeoTestDomain];
        $this->assertEquals(-55.12, $element1->getOrderLineSubtotal());
        $this->assertEquals(-11.02, $element1->getOrderLineTaxes());
        $this->assertEquals(-66.14, $element1->getOrderLineTotal());

        $this->assertEquals($originalBalance, $this->api->account()->balance());

        try {
            $this->api->domains()->get($rodeoTestDomain);
            $this->fail("Should have thrown here");
        } catch (TransactionException $e) {
            // Expected
        }


    }


    public function testCanTransferDomainInWithPrivacyProxySetAndThenUpdateContacts() {

        $testDomains = $this->api->test()->createPullTransferRodeoDomains(1);

        $rodeoTestDomain = $testDomains[0][0];
        $rodeoTestAuth = $testDomains[0][1];

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        // Start incoming transfers
        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($rodeoTestDomain . "," . $rodeoTestAuth), $owner, null, null, null, 1));
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());

        $this->api->test()->approveIncomingTransferOtherRegistrar(array($rodeoTestDomain));


        $tech = new DomainNameContact("Mary Jane", "jane@oxil.co.uk", "My org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));


        $result = $this->api->domains()->update(new DomainNameUpdateDescriptor([$rodeoTestDomain], null, $tech, $tech, $tech));

        $this->assertEquals("SUCCEEDED", $result->getTransactionStatus());

        $domain = $this->api->domains()->get($rodeoTestDomain);
        $this->assertEquals("Mary Jane", $domain->getTechnicalContact()->getName());


    }


    public function testCheckingTransferStatusReturnsStatusObjectsOrThrowsExceptionsIfNotInTransfer() {

        $rodeoDomains = $this->api->test()->createPullTransferRodeoDomains(2);


        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Hello", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        // Start incoming transfers
        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($rodeoDomains[0][0] . "," . $rodeoDomains[0][1], $rodeoDomains[1][0] . "," . $rodeoDomains[1][1]), $owner));
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        // Now do a transfer check
        $checks = $this->api->domains()->transferCheck($rodeoDomains[0][0]);
        $this->assertEquals("TRANSFER_IN_AWAITING_RESPONSE", $checks->getStatus());
        $this->assertEquals($rodeoDomains[0][0], $checks->getDomainName());
        $this->assertNotNull($checks->getDomainExpiryDate());
        $this->assertNotNull($checks->getTransferExpiryDate());
        $this->assertNotNull($checks->getTransferStartedDate());
        $this->assertEquals("Pending", $checks->getTransferStatus());

        // Now do a transfer check
        $checks = $this->api->domains()->transferCheck($rodeoDomains[1][0]);
        $this->assertEquals("TRANSFER_IN_AWAITING_RESPONSE", $checks->getStatus());
        $this->assertEquals($rodeoDomains[1][0], $checks->getDomainName());
        $this->assertNotNull($checks->getDomainExpiryDate());
        $this->assertNotNull($checks->getTransferExpiryDate());
        $this->assertNotNull($checks->getTransferStartedDate());
        $this->assertEquals("Pending", $checks->getTransferStatus());


        // Now complete and check
        $this->api->test()->approveIncomingTransferOtherRegistrar(array($rodeoDomains[0][0]));
        $this->api->test()->rejectIncomingTransferOtherRegistrar(array($rodeoDomains[1][0]));

        try {
            $this->api->domains()->transferCheck($rodeoDomains[0][0]);
            $this->fail("Should have thrown here");
        } catch (TransactionException $e) {
            $this->assertEquals("DOMAIN_TRANSFER_NOT_MID_TRANSFER", $e->getTransactionErrors()["DOMAIN_TRANSFER_NOT_MID_TRANSFER"]->getCode());
        }

        try {
            $this->api->domains()->transferCheck($rodeoDomains[1][0]);
            $this->fail("Should have thrown here");
        } catch (TransactionException $e) {
            $this->assertEquals("DOMAIN_TRANSFER_NOT_MID_TRANSFER", $e->getTransactionErrors()["DOMAIN_TRANSFER_NOT_MID_TRANSFER"]->getCode());
        }
    }


    public function testCannotTransferPremiumDomainsIfMissingOrInvalidPremiumTransferCodes() {


        $premiums = $this->api->test()->createTransferPremiums();

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        list($domainName1, $authCode1) = $premiums[0];
        list($domainName2, $authCode2) = $premiums[1];


        // Missing transfer code
        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor([$domainName1 . "," . $authCode1,
            $domainName2 . "," . $authCode2], $owner));
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element1 = $transaction->getTransactionElements()[$domainName1];
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(["DOMAIN_MISSING_PREMIUM_TRANSFER_CODE"], array_keys($element1->getElementErrors()));


        $element2 = $transaction->getTransactionElements()[$domainName2];
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $this->assertEquals(["DOMAIN_MISSING_PREMIUM_TRANSFER_CODE"], array_keys($element2->getElementErrors()));


        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor([$domainName1 . "," . $authCode1,
            $domainName2 . "," . $authCode2], $owner, $owner, $owner, $owner, 1, 1, [
            $domainName1 => "BADPREMIUMTRANSFERCODE",
            $domainName2 => "ANOTHERBADCODE"
        ]));
        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());

        $element1 = $transaction->getTransactionElements()[$domainName1];
        $this->assertEquals("FAILED", $element1->getElementStatus());
        $this->assertEquals(["DOMAIN_INVALID_PREMIUM_TRANSFER_CODE"], array_keys($element1->getElementErrors()));


        $element2 = $transaction->getTransactionElements()[$domainName2];
        $this->assertEquals("FAILED", $element2->getElementStatus());
        $this->assertEquals(["DOMAIN_INVALID_PREMIUM_TRANSFER_CODE"], array_keys($element2->getElementErrors()));


    }


    public function testCanTransferPremiumDomainIfValidPremiumTransferCodeSupplied() {

        $premiums = $this->api->test()->createTransferPremiums();

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        list($domainName1, $authCode1) = $premiums[0];
        list($domainName2, $authCode2) = $premiums[1];

        $transferCode1 = $this->api->domains()->liveAvailability($domainName1)->getAdditionalData()["premiumTransferCode"];
        $transferCode2 = $this->api->domains()->liveAvailability($domainName2)->getAdditionalData()["premiumTransferCode"];

        // Missing transfer code
        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor([$domainName1 . "," . $authCode1,
            $domainName2 . "," . $authCode2], $owner, $owner, $owner, $owner, 1, 1, [
            $domainName1 => $transferCode1,
            $domainName2 => $transferCode2
        ]));


        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        $domainNameObj1 = $this->api->domains()->get($domainName1);
        $this->assertEquals("TRANSFER_IN_AWAITING_RESPONSE", $domainNameObj1->getStatus());

        $this->api->domains()->transferCancel([$domainName1]);

        $domainNameObj2 = $this->api->domains()->get($domainName2);
        $this->assertEquals("TRANSFER_IN_AWAITING_RESPONSE", $domainNameObj2->getStatus());

        $this->api->domains()->transferCancel([$domainName2]);

    }


    public function testAccountNotificationsAreCorrectlyAddedWhenPullTransferInsAreCompletedOrRejected() {

        $testDomains = $this->api->test()->createPullTransferRodeoDomains(2);

        $rodeoTestDomain = $testDomains[0][0];
        $rodeoTestAuth = $testDomains[0][1];

        $rodeoTestDomain2 = $testDomains[1][0];
        $rodeoTestAuth2 = $testDomains[1][1];


        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB", null, null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));


        // Start incoming transfers
        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($rodeoTestDomain . "," . $rodeoTestAuth, $rodeoTestDomain2 . "," . $rodeoTestAuth2), $owner));
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


        // Now check the account notifications
        $accountNotifications = $this->api->account()->listNotifications();
        $this->assertTrue($accountNotifications[0] instanceof AccountNotification);
        $this->assertEquals("Domain Transfer In", $accountNotifications[0]->getNotificationType());
        $this->assertEquals("Initiated", $accountNotifications[0]->getNotificationSubType());
        $this->assertEquals($rodeoTestDomain2, $accountNotifications[0]->getRefersTo());
        $this->assertNotNull($accountNotifications[0]->getMessage());

        $this->assertTrue($accountNotifications[1] instanceof AccountNotification);
        $this->assertTrue($accountNotifications[1] instanceof AccountNotification);
        $this->assertEquals("Domain Transfer In", $accountNotifications[1]->getNotificationType());
        $this->assertEquals("Initiated", $accountNotifications[1]->getNotificationSubType());
        $this->assertEquals($rodeoTestDomain, $accountNotifications[1]->getRefersTo());
        $this->assertNotNull($accountNotifications[1]->getMessage());

        $this->api->test()->approveIncomingTransferOtherRegistrar(array($rodeoTestDomain));
        $this->api->test()->rejectIncomingTransferOtherRegistrar(array($rodeoTestDomain2));

        // Now check the account notifications
        $accountNotifications = $this->api->account()->listNotifications();
        $this->assertTrue($accountNotifications[0] instanceof AccountNotification);
        $this->assertEquals("Domain Transfer In", $accountNotifications[0]->getNotificationType());
        $this->assertEquals("Rejected", $accountNotifications[0]->getNotificationSubType());
        $this->assertEquals($rodeoTestDomain2, $accountNotifications[0]->getRefersTo());
        $this->assertNotNull($accountNotifications[0]->getMessage());

        $this->assertTrue($accountNotifications[1] instanceof AccountNotification);
        $this->assertEquals("Domain Transfer In", $accountNotifications[1]->getNotificationType());
        $this->assertEquals("Completed", $accountNotifications[1]->getNotificationSubType());
        $this->assertEquals($rodeoTestDomain, $accountNotifications[1]->getRefersTo());
        $this->assertNotNull($accountNotifications[1]->getMessage());

    }


    public function testAccountNotificationsAreCorrectlyAddedWhenPushTransferInsAreCompletedOrRejected() {

        $domainNames = $this->api->test()->createPushTransferUKDomains(2);
        $domain1 = $domainNames[0];
        $domain2 = $domainNames[1];

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB", null, null, null, null, null, null, array("nominetRegistrantType" => "IND"));

        $transaction = $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($domain1, $domain2), $owner));

        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());

        $this->api->test()->acceptOwnershipConfirmation(array($domain1));
        $this->api->test()->declineOwnershipConfirmation(array($domain2));

        // Now check the account notifications
        $accountNotifications = $this->api->account()->listNotifications();
        $this->assertTrue($accountNotifications[0] instanceof AccountNotification);
        $this->assertEquals("Domain Transfer In", $accountNotifications[0]->getNotificationType());
        $this->assertEquals("Declined", $accountNotifications[0]->getNotificationSubType());
        $this->assertEquals($domain2, $accountNotifications[0]->getRefersTo());
        $this->assertNotNull($accountNotifications[0]->getMessage());

        $this->assertTrue($accountNotifications[1] instanceof AccountNotification);
        $this->assertTrue($accountNotifications[1] instanceof AccountNotification);
        $this->assertEquals("Domain Transfer In", $accountNotifications[1]->getNotificationType());
        $this->assertEquals("Completed", $accountNotifications[1]->getNotificationSubType());
        $this->assertEquals($domain1, $accountNotifications[1]->getRefersTo());
        $this->assertNotNull($accountNotifications[1]->getMessage());

    }


}
