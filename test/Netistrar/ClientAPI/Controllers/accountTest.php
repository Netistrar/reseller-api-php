<?php

namespace Netistrar\ClientAPI\Controllers;


use Netistrar\ClientAPI\Objects\Domain\Descriptor\DomainNameTransferDescriptor;
use Netistrar\ClientAPI\Objects\Domain\DomainNameContact;

include_once "ClientAPITestBase.php";

class accountTest extends \ClientAPITestBase {

    public function testCanRetrieveCurrentBalance() {

        $this->api->test()->updateAccountBalance(10000);

        $result = $this->api->account()->balance();
        $this->assertEquals(10000, $result);

        $this->api->test()->updateAccountBalance(7500);

        $result = $this->api->account()->balance();
        $this->assertEquals(7500, $result);

    }


    public function testCanListAndGetSingleAccountNotifications() {

        $newDomains = $this->api->test()->createPullTransferRodeoDomains(2);

        $rodeoTestDomain = $newDomains[0][0];
        $rodeoTestAuth = $newDomains[0][1];
        $rodeoTestIdentifier = $rodeoTestDomain . "," . $rodeoTestAuth;

        $rodeoTestDomain2 = $newDomains[1][0];
        $rodeoTestAuth2 = $newDomains[1][1];
        $rodeoTestIdentifier2 = $rodeoTestDomain2 . "," . $rodeoTestAuth2;

        $validContact = new DomainNameContact("Test", "test@test.com", "", "hello street", "hello road", "hello", "helloshire", "he12 144", "GB", "", "", "", "", "", "", array(), array("nominetRegistrantType" => "IND"));

        $this->api->domains()->transferCreate(new DomainNameTransferDescriptor(array($rodeoTestIdentifier, $rodeoTestIdentifier2), $validContact, null, null, null, 1, true));


        $this->api->test()->approveIncomingTransferOtherRegistrar(array($rodeoTestDomain));


        $this->api->test()->rejectIncomingTransferOtherRegistrar(array($rodeoTestDomain2));

        $allAccountNotifications = $this->api->account()->listNotifications();
        $this->assertTrue(sizeof($allAccountNotifications) >= 4);

        $firstNotification = $allAccountNotifications[0];
        $secondNotification = $allAccountNotifications[1];
        $thirdNotification = $allAccountNotifications[2];
        $fourthNotification = $allAccountNotifications[3];

        $this->assertEquals("Domain Transfer In - Rejected", $firstNotification->getNotificationType() . " - " . $firstNotification->getNotificationSubType());
        $this->assertEquals("Domain Transfer In - Completed", $secondNotification->getNotificationType() . " - " . $secondNotification->getNotificationSubType());
        $this->assertEquals("Domain Transfer In - Initiated", $thirdNotification->getNotificationType() . " - " . $thirdNotification->getNotificationSubType());
        $this->assertEquals("Domain Transfer In - Initiated", $fourthNotification->getNotificationType() . " - " . $fourthNotification->getNotificationSubType());


        $limitedNotifications = $this->api->account()->listNotifications(2);
        $this->assertEquals(2, sizeof($limitedNotifications));
        $this->assertEquals($firstNotification, $limitedNotifications[0]);
        $this->assertEquals($secondNotification, $limitedNotifications[1]);

        $limitedNotifications = $this->api->account()->listNotifications(50, "Domain Transfer In", "Initiated");
        $this->assertTrue(sizeof($limitedNotifications) >= 2);
        $this->assertEquals($thirdNotification, $limitedNotifications[0]);
        $this->assertEquals($fourthNotification, $limitedNotifications[1]);

        $limitedNotifications = $this->api->account()->listNotifications(50, "Domain Transfer In", "Completed");
        $this->assertTrue(sizeof($limitedNotifications) >= 1);
        $this->assertEquals($secondNotification, $limitedNotifications[0]);

        $limitedNotifications = $this->api->account()->listNotifications(50, "Domain Transfer In", "Completed,Rejected");
        $this->assertTrue(sizeof($limitedNotifications) >= 2);
        $this->assertEquals($firstNotification, $limitedNotifications[0]);
        $this->assertEquals($secondNotification, $limitedNotifications[1]);


        $this->assertEquals($firstNotification, $this->api->account()->getNotification($firstNotification->getId()));
        $this->assertEquals($secondNotification, $this->api->account()->getNotification($secondNotification->getId()));
        $this->assertEquals($thirdNotification, $this->api->account()->getNotification($thirdNotification->getId()));
        $this->assertEquals($fourthNotification, $this->api->account()->getNotification($fourthNotification->getId()));


    }


}


