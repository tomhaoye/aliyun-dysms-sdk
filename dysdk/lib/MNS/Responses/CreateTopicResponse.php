<?php
namespace Aliyun\DayuSDK\MNS\Responses;

use Aliyun\DayuSDK\MNS\Constants;
use Aliyun\DayuSDK\MNS\Exception\MnsException;
use Aliyun\DayuSDK\MNS\Exception\TopicAlreadyExistException;
use Aliyun\DayuSDK\MNS\Exception\InvalidArgumentException;
use Aliyun\DayuSDK\MNS\Responses\BaseResponse;
use Aliyun\DayuSDK\MNS\Common\XMLParser;

class CreateTopicResponse extends BaseResponse
{
    private $topicName;

    public function __construct($topicName)
    {
        $this->topicName = $topicName;
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 201 || $statusCode == 204) {
            $this->succeed = TRUE;
        } else {
            $this->parseErrorResponse($statusCode, $content);
        }
    }

    public function parseErrorResponse($statusCode, $content, MnsException $exception = NULL)
    {
        $this->succeed = FALSE;
        $xmlReader = $this->loadXmlContent($content);

        try {
            $result = XMLParser::parseNormalError($xmlReader);

            if ($result['Code'] == Constants::INVALID_ARGUMENT)
            {
                throw new InvalidArgumentException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::TOPIC_ALREADY_EXIST)
            {
                throw new TopicAlreadyExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            throw new MnsException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
        } catch (\Exception $e) {
            if ($exception != NULL) {
                throw $exception;
            } elseif($e instanceof MnsException) {
                throw $e;
            } else {
                throw new MnsException($statusCode, $e->getMessage());
            }
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }
    }

    public function getTopicName()
    {
        return $this->topicName;
    }
}

?>
