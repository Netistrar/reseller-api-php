<?php


namespace Netistrar\ClientAPI\Controllers;

use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameAvailabilityDescriptor;
use Netistrar\ClientAPI\Objects\Domain\DomainAvailability;
use Netistrar\ClientAPI\Objects\Domain\DomainAvailabilityPrice;
use Netistrar\ClientAPI\Objects\Domain\DomainAvailabilityResults;

include_once "ClientAPITestBase.php";


class domainAvailabilityTest extends \ClientAPITestBase {

    /**
     * Test we can get hinted domain availability via the API.
     */
    public function testCanGetHintedDomainAvailabilityViaAPI() {

        $availability = $this->api->domains()->hintedAvailability(new DomainNameAvailabilityDescriptor("test.com"));

        $this->assertTrue($availability instanceof DomainAvailabilityResults);
        $directResult = $availability->getDirectResult();
        $this->assertTrue($directResult instanceof DomainAvailability);

        $this->assertEquals("HINTED_UNAVAILABLE", $directResult->getAvailability());
        $this->assertEquals(1, sizeof($directResult->getPrices()["transfer"]));


        $availability = $this->api->domains()->hintedAvailability(new DomainNameAvailabilityDescriptor("bodybuilding", null, array("com", "net", "org"), true));

        $this->assertEquals("bodybuilding.com", $availability->getTldResults()["com"]->getDomainName());
        $this->assertTrue(sizeof($availability->getSuggestions()["org"]) > 0);


    }


    public function testCanGetLiveAvailabilityViaAPI() {

        $availability = $this->api->domains()->liveAvailability("james.uk");
        $this->assertTrue($availability instanceof DomainAvailability);
        $this->assertEquals("AVAILABLE", $availability->getAvailability());
        $this->assertEquals(10, sizeof($availability->getPrices()["registration"]));


        $this->assertTrue($availability->getPrices()["registration"][0] instanceof DomainAvailabilityPrice);


    }


    public function testCanGetAllTLDsOptionallyLimitedByCategory() {

        $tlds = $this->api->domains()->tldList();
        $this->assertTrue(is_array($tlds));
        $this->assertTrue(sizeof($tlds) > 100);
        $this->assertTrue(in_array("uk", $tlds));
        $this->assertTrue(in_array("com", $tlds));

        $tlds = $this->api->domains()->tldList("National");
        $this->assertEquals(6, sizeof($tlds));
        $this->assertTrue(in_array("uk", $tlds));
        $this->assertTrue(in_array("scot", $tlds));

        $tlds = $this->api->domains()->tldList("Popular");
        $this->assertEquals(8, sizeof($tlds));
        $this->assertTrue(in_array("uk", $tlds));
        $this->assertTrue(in_array("com", $tlds));

    }


    public function testCanGetAllTLDCategories() {

        $categories = $this->api->domains()->tldCategoryList();
        $this->assertTrue(is_array($categories));
        $this->assertTrue(in_array("Popular", $categories));
        $this->assertTrue(in_array("National", $categories));
        $this->assertTrue(in_array("International", $categories));
    }


}