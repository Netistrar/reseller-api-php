<?php


namespace Netistrar\ClientAPI\Controllers;


use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameCreateDescriptor;
use Netistrar\ClientAPI\Objects\Domain\DomainNameContact;
use Netistrar\ClientAPI\Objects\Domain\DomainNameError;
use Netistrar\ClientAPI\Objects\Domain\DomainNameOperationError;
use Netistrar\ClientAPI\Objects\Domain\DomainNameTransaction;


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
    }


    public function testFailedTransactionIsReturnedIfValidationErrorsOnCreate() {

        $ukDomain = "validationdomain.uk";
        $comDomain = "validationdomain.com";
        $owner = new DomainNameContact();


        // Try a single one.
        $domainTransaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($ukDomain), 1, $owner, array("monkeynameserver")));


        $this->assertTrue($domainTransaction instanceof DomainNameTransaction);

        $this->assertEquals(1, sizeof($domainTransaction->getTransactionElements()));

        // Expected
        $element = $domainTransaction->getTransactionElements()[$ukDomain];

        $validationErrors = $element->getElementErrors();
        $this->assertTrue(is_array($validationErrors));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_OWNER_CONTACT"]));
        $this->assertTrue(isset($validationErrors["DOMAIN_INVALID_NAMESERVER_FORMAT"]));


        // Try a multiple one.
        $domainTransaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($ukDomain, $comDomain), 1, $owner, array("monkeynameserver")));

        $this->assertTrue($domainTransaction instanceof DomainNameTransaction);

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
        $myDomain = "businessasusual.computer";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My Org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($existingDomain, $myDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com")));

        $this->assertTrue($transaction instanceof DomainNameTransaction);
        $this->assertEquals(2, sizeof($transaction->getTransactionElements()));

        $elementErrors = $transaction->getTransactionElements()[$existingDomain]->getElementErrors();
        $this->assertEquals(1, sizeof($elementErrors));
        $this->assertTrue(isset($elementErrors["DOMAIN_UNAVAILABLE_FOR_REGISTRATION"]));

        $elementErrors = $transaction->getTransactionElements()[$myDomain]->getElementErrors();
        $this->assertEquals(1, sizeof($elementErrors));
        $this->assertTrue(isset($elementErrors["DOMAIN_ALREADY_IN_ACCOUNT"]));

    }

    public function testOperationErrorsOccurIfUnexpectedCreateError() {

        $rightsUK = "ganymede-netistrar.uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "myorg", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($rightsUK), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com")));


        $this->assertEquals("ALL_ELEMENTS_FAILED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertNotNull($transaction->getNetistrarOrderId());
        $this->assertEquals("GBP", $transaction->getNetistrarOrderCurrency());
        $this->assertEquals(0, $transaction->getNetistrarOrderSubtotal());
        $this->assertEquals(0, $transaction->getNetistrarOrderTaxes());
        $this->assertEquals(0, $transaction->getNetistrarOrderTotal());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();
        $element = $elements[$rightsUK];
        $this->assertEquals($rightsUK, $element->getDomainName());
        $this->assertEquals("FAILED", $element->getElementStatus());
        $this->assertEquals(array(), $element->getOperationData());
        $this->assertEquals(0, $element->getNetistrarOrderLineSubtotal());
        $this->assertEquals(0, $element->getNetistrarOrderLineTaxes());
        $this->assertEquals(0, $element->getNetistrarOrderLineTotal());
        $this->assertEquals(1, sizeof($element->getElementErrors()));


        $elementError = $element->getElementErrors()["DOMAIN_REGISTRATION_ERROR"];
        $this->assertTrue($elementError instanceof DomainNameError);
        $this->assertEquals("DOMAIN_REGISTRATION_ERROR", $elementError->getCode());


        try {
            // Now confirm that the registration didn't actually take place.
            $domainName = $this->api->domains()->get($rightsUK);
            $this->fail("Should have thrown here");
        } catch (\Exception $e) {
            // Success
        }

    }

    public function testCanCreateValidSingleUKDomainNameWithAllAssociatedAssetsAndTransactionIsReturned() {

        $newUKDomain = "validdomain-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "Test org", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $key = $this->api->utility()->createBulkOperation();

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 1, 1, $key));


        $this->assertTrue($transaction instanceof DomainNameTransaction);
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_CREATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());
        $this->assertNotNull($transaction->getNetistrarOrderId());
        $this->assertEquals("GBP", $transaction->getNetistrarOrderCurrency());
        $this->assertEquals(4.50, $transaction->getNetistrarOrderSubtotal());
        $this->assertEquals(0.9, $transaction->getNetistrarOrderTaxes());
        $this->assertEquals(5.40, $transaction->getNetistrarOrderTotal());

        $this->assertEquals(1, sizeof($transaction->getTransactionElements()));
        $elements = $transaction->getTransactionElements();
        $element = $elements[$newUKDomain];
        $this->assertEquals($newUKDomain, $element->getDomainName());
        $this->assertEquals("SUCCEEDED", $element->getElementStatus());
        $expiryDate = new \DateTime();
        $expiryDate->add(new \DateInterval("P1Y"));
        $this->assertEquals(array("registrationYears" => 1, "expiryDate" => $expiryDate->format("d/m/Y")), $element->getOperationData());
        $this->assertEquals(4.50, $element->getNetistrarOrderLineSubtotal());
        $this->assertEquals(0.9, $element->getNetistrarOrderLineTaxes());
        $this->assertEquals(5.40, $element->getNetistrarOrderLineTotal());


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

        $transaction = $this->api->domains()->create(new DomainNameCreateDescriptor(array($newBlogDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 0, 1));

        $this->assertTrue($transaction instanceof DomainNameTransaction);
        $this->assertNotNull($transaction->getTransactionDateTime());
        $this->assertEquals("DOMAIN_CREATE", $transaction->getTransactionType());
        $this->assertEquals("SUCCEEDED", $transaction->getTransactionStatus());


    }


    public function testWhenPrivacySettingSetToTwoThisIsCorrectlyReflectedOnCreate() {

        $newUKDomain = "validdomain-" . date("U") . ".uk";

        $owner = new DomainNameContact("Marky Babes", "mark@oxil.co.uk", "My ORG", "33 My Street", null, "Oxford", "Oxon", "OX4 2RD", "GB");
        $owner->setAdditionalData(array("nominetRegistrantType" => "IND"));

        $key = $this->api->utility()->createBulkOperation();

        $this->api->domains()->create(new DomainNameCreateDescriptor(array($newUKDomain), 1, $owner, array("ns1.netistrar.com", "ns2.netistrar.com"), null, null, null, 2, 1), $key);

        // Now confirm that the registration actually took place correctly.
        $domainName = $this->api->domains()->get($newUKDomain);
        $this->assertEquals("Marky Babes", $domainName->getOwnerContact()->getName());
        $this->assertEquals("IND", $domainName->getOwnerContact()->getAdditionalData()["nominetRegistrantType"]);
        $this->assertEquals(1, $domainName->getLocked());
        $this->assertNull($domainName->getLockedUntil());
        $this->assertEquals(2, $domainName->getPrivacyProxy());
        $this->assertEquals(1, $domainName->getAutoRenew());
    }


}