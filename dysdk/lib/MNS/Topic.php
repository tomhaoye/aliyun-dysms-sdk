<?php
namespace Aliyun\DayuSDK\MNS;

use Aliyun\DayuSDK\MNS\Http\HttpClient;
use Aliyun\DayuSDK\MNS\AsyncCallback;
use Aliyun\DayuSDK\MNS\Model\TopicAttributes;
use Aliyun\DayuSDK\MNS\Model\SubscriptionAttributes;
use Aliyun\DayuSDK\MNS\Model\UpdateSubscriptionAttributes;
use Aliyun\DayuSDK\MNS\Requests\SetTopicAttributeRequest;
use Aliyun\DayuSDK\MNS\Responses\SetTopicAttributeResponse;
use Aliyun\DayuSDK\MNS\Requests\GetTopicAttributeRequest;
use Aliyun\DayuSDK\MNS\Responses\GetTopicAttributeResponse;
use Aliyun\DayuSDK\MNS\Requests\PublishMessageRequest;
use Aliyun\DayuSDK\MNS\Responses\PublishMessageResponse;
use Aliyun\DayuSDK\MNS\Requests\SubscribeRequest;
use Aliyun\DayuSDK\MNS\Responses\SubscribeResponse;
use Aliyun\DayuSDK\MNS\Requests\UnsubscribeRequest;
use Aliyun\DayuSDK\MNS\Responses\UnsubscribeResponse;
use Aliyun\DayuSDK\MNS\Requests\GetSubscriptionAttributeRequest;
use Aliyun\DayuSDK\MNS\Responses\GetSubscriptionAttributeResponse;
use Aliyun\DayuSDK\MNS\Requests\SetSubscriptionAttributeRequest;
use Aliyun\DayuSDK\MNS\Responses\SetSubscriptionAttributeResponse;
use Aliyun\DayuSDK\MNS\Requests\ListSubscriptionRequest;
use Aliyun\DayuSDK\MNS\Responses\ListSubscriptionResponse;

class Topic
{
    private $topicName;
    private $client;

    public function __construct(HttpClient $client, $topicName)
    {
        $this->client = $client;
        $this->topicName = $topicName;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function setAttribute(TopicAttributes $attributes)
    {
        $request = new SetTopicAttributeRequest($this->topicName, $attributes);
        $response = new SetTopicAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function getAttribute()
    {
        $request = new GetTopicAttributeRequest($this->topicName);
        $response = new GetTopicAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function generateQueueEndpoint($queueName)
    {
        return "acs:mns:" . $this->client->getRegion() . ":" . $this->client->getAccountId() . ":queues/" . $queueName;
    }

    public function generateMailEndpoint($mailAddress)
    {
        return "mail:directmail:" . $mailAddress;
    }

    public function generateSmsEndpoint($phone = null)
    {
        if ($phone)
        {
            return "sms:directsms:" . $phone;
        }
        else
        {
            return "sms:directsms:anonymous";
        }
    }

    public function generateBatchSmsEndpoint()
    {
        return "sms:directsms:anonymous";
    }

    public function publishMessage(PublishMessageRequest $request)
    {
        $request->setTopicName($this->topicName);
        $response = new PublishMessageResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function subscribe(SubscriptionAttributes $attributes)
    {
        $attributes->setTopicName($this->topicName);
        $request = new SubscribeRequest($attributes);
        $response = new SubscribeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function unsubscribe($subscriptionName)
    {
        $request = new UnsubscribeRequest($this->topicName, $subscriptionName);
        $response = new UnsubscribeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function getSubscriptionAttribute($subscriptionName)
    {
        $request = new GetSubscriptionAttributeRequest($this->topicName, $subscriptionName);
        $response = new GetSubscriptionAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function setSubscriptionAttribute(UpdateSubscriptionAttributes $attributes)
    {
        $attributes->setTopicName($this->topicName);
        $request = new SetSubscriptionAttributeRequest($attributes);
        $response = new SetSubscriptionAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function listSubscription($retNum = NULL, $prefix = NULL, $marker = NULL)
    {
        $request = new ListSubscriptionRequest($this->topicName, $retNum, $prefix, $marker);
        $response = new ListSubscriptionResponse();
        return $this->client->sendRequest($request, $response);
    }
}

?>
