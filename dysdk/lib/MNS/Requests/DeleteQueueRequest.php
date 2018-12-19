<?php
namespace Aliyun\DayuSDK\MNS\Requests;

use Aliyun\DayuSDK\MNS\Constants;
use Aliyun\DayuSDK\MNS\Requests\BaseRequest;
use Aliyun\DayuSDK\MNS\Model\QueueAttributes;

class DeleteQueueRequest extends BaseRequest
{
    private $queueName;

    public function __construct($queueName)
    {
        parent::__construct('delete', 'queues/' . $queueName);
        $this->queueName = $queueName;
    }

    public function getQueueName()
    {
        return $this->queueName;
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
