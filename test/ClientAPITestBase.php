<?php
/**
 * Created by PhpStorm.
 * User: markrobertshaw
 * Date: 23/10/2018
 * Time: 15:48
 */

include_once "autoloader.php";

class ClientAPITestBase extends \PHPUnit\Framework\TestCase {


    const HOST_NAME = "http://restapi.netistrar.test:8080";
    const API_KEY = "TESTAPIKEY";
    const API_SECRET = "TESTAPISECRET";

//    const HOST_NAME = "http://restapi.netistrar-ote.uk";
//    const API_KEY = "Ka5mPbxtMY";
//    const API_SECRET = "U3plgiTpQr";

    /**
     * @var \Netistrar\ClientAPI\APIProvider
     */
    protected $api;

    public function setUp() {
        $this->api = new \Netistrar\ClientAPI\APIProvider(self::HOST_NAME, self::API_KEY, self::API_SECRET);
    }


}
