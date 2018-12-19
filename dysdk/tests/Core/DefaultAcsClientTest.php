<?php

namespace Aliyun\DayuSDK\Test\Core;

use PHPUnit\Framework\TestCase;
use Aliyun\DayuSDK\Core\Profile\DefaultProfile;
use Aliyun\DayuSDK\Core\Config;
use Aliyun\DayuSDK\Core\DefaultAcsClient;
use Aliyun\DayuSDK\Test\Core\Ecs\Request\DescribeRegionsRequest;

class DefaultAcsClientTest extends TestCase {

    function setUp() {
        Config::load();
    }

	public function testDoActionRPC() {
        echo "\nWARNING: setup accessKeyId and accessSecret of DefaultAcsClientTest";
        $iClientProfile = DefaultProfile::getProfile(
            "cn-hangzhou",
            "yourAccessKeyId",
            "yourAccessKeySecret"
        );
		$request = new DescribeRegionsRequest();
        $client = new DefaultAcsClient($iClientProfile);
        $response = $client->getAcsResponse($request);
		
		$this->assertNotNull($response->RequestId);
		$this->assertNotNull($response->Regions->Region[0]->LocalName);
		$this->assertNotNull($response->Regions->Region[0]->RegionId);
	}
}