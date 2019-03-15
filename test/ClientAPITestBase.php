<?php
/**
 * Created by PhpStorm.
 * User: markrobertshaw
 * Date: 23/10/2018
 * Time: 15:48
 */

include_once "autoloader.php";

class ClientAPITestBase extends \PHPUnit\Framework\TestCase {

    /**
     * @var \Netistrar\ClientAPI\APIProvider
     */
    protected $api;

    public function setUp() {
        $this->api = new \Netistrar\ClientAPI\APIProvider("http://restapi.netistrar.test:8080", "TESTAPIKEY", "TESTAPISECRET");
    }


}