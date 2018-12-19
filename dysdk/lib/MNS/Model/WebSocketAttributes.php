<?php
namespace Aliyun\DayuSDK\MNS\Model;

use Aliyun\DayuSDK\MNS\Constants;
use Aliyun\DayuSDK\MNS\Exception\MnsException;

class WebSocketAttributes
{
    public $importanceLevel;

    public function __construct($importanceLevel)
    {
        $this->importanceLevel = $importanceLevel;
    }

    public function setImportanceLevel($importanceLevel)
    {
        $this->importanceLevel = $importanceLevel;
    }

    public function getImportanceLevel()
    {
        return $this->importanceLevel;
    }

    public function writeXML(\XMLWriter $xmlWriter)
    {
        $jsonArray = array(Constants::IMPORTANCE_LEVEL => $this->importanceLevel);
        $xmlWriter->writeElement(Constants::WEBSOCKET, json_encode($jsonArray));
    }
}

?>
