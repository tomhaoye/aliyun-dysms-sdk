<?php

namespace Aliyun\DayuSDK\Test\Core\Auth;

use PHPUnit\Framework\TestCase;
use Aliyun\DayuSDK\Core\Config;
use Aliyun\DayuSDK\Core\Auth\ShaHmac1Signer;

class ShaHmac1SignerTest extends TestCase
{
    public function setUp()
    {
        Config::load();
    }

    public function testShaHmac1Signer()
    {
        $signer = new ShaHmac1Signer();
        $this->assertEquals("33nmIV5/p6kG/64eLXNljJ5vw84=",
            $signer->signString("this is a ShaHmac1 test.", "accessSecret"));
    }
}