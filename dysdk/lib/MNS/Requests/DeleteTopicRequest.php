<?php
namespace Aliyun\DayuSDK\MNS\Requests;

use Aliyun\DayuSDK\MNS\Constants;
use Aliyun\DayuSDK\MNS\Requests\BaseRequest;
use Aliyun\DayuSDK\MNS\Model\TopicAttributes;

class DeleteTopicRequest extends BaseRequest
{
    private $topicName;

    public function __construct($topicName)
    {
        parent::__construct('delete', 'topics/' . $topicName);
        $this->topicName = $topicName;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function generateBody()
    {
        return NULL;
    }

    public function generateQueryString()
    {
        return NULL;
    }
}
?>
