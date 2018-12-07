<?php
/**
 * Created by PhpStorm.
 * User: markrobertshaw
 * Date: 24/10/2018
 * Time: 16:20
 */

namespace Netistrar\ClientAPI\Controllers;


use Netistrar\ClientAPI\Exception\RateLimitExceededException;
use Netistrar\ClientAPI\Objects\Utility\BulkOperationProgress;

include_once "ClientAPITestBase.php";

class utilityTest extends \ClientAPITestBase {


    public function testCanPing() {
        $this->assertEquals("OK", $this->api->utility()->ping());
    }

    public function testCanCreateABulkOperation() {

        $bulkOperationKey = $this->api->utility()->createBulkOperation();
        $this->assertNotNull($bulkOperationKey);

    }


    public function testCanGetBulkOperationProgress() {

        $bulkOperationKey = $this->api->utility()->createBulkOperation();

        $status = $this->api->utility()->getBulkOperationProgress($bulkOperationKey);
        $this->assertTrue($status instanceof BulkOperationProgress);

        $this->assertEquals("PENDING", $status->getStatus());

    }


    public function testRateLimitExceededExceptionRaisedWhenRateExceeded() {

        try {
            for ($i = 0; $i < 11; $i++) {
                $this->api->utility()->ping();
            }
            $this->fail("should have failed");
        } catch (RateLimitExceededException $e) {
        }

        $this->assertTrue(true);

    }


}