阿里云-云通信PHP版SDK
---
<p>
<a href="#"><img src="https://img.shields.io/github/languages/top/tomhaoye/aliyun-dysms-sdk" alt="lang"></a>
<a href="#"><img src="https://img.shields.io/github/languages/code-size/tomhaoye/aliyun-dysms-sdk" alt="size"></a>
<a href="#"><img src="https://img.shields.io/github/last-commit/tomhaoye/aliyun-dysms-sdk" alt="last"></a>
<a href="#"><img src="https://img.shields.io/packagist/dt/tomhaoye/aliyun-dysms-sdk" alt="download"></a>
</p>

## composer安装
```bash
composer require tomhaoye/aliyun-dysms-sdk
```

## 官方Demo
```php
<?php

use Aliyun\DayuSDK\Core\Config;
use Aliyun\DayuSDK\Core\Profile\DefaultProfile;
use Aliyun\DayuSDK\Core\DefaultAcsClient;
use Aliyun\DayuSDK\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\DayuSDK\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\DayuSDK\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

// 加载区域结点配置
Config::load();

/**
 * Class SmsDemo
 *
 * 这是短信服务API产品的DEMO程序，直接执行此文件即可体验短信服务产品API功能
 * (只需要将AK替换成开通了云通信-短信服务产品功能的AK即可)
 * 备注:Demo工程编码采用UTF-8
 */
class SmsDemo
{

    static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient()
    {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = "yourAccessKeyId"; // AccessKeyId

        $accessKeySecret = "yourAccessKeySecret"; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if (static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public static function sendSms()
    {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers("12345678901");

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName("短信签名");

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode("SMS_0000001");

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code" => "12345",
            "product" => "dsd"
        ), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        $request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * 批量发送短信
     * @return stdClass
     */
    public static function sendBatchSms()
    {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendBatchSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填:待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumberJson(json_encode(array(
            "1500000000",
            "1500000001",
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信签名-支持不同的号码发送不同的短信签名
        $request->setSignNameJson(json_encode(array(
            "云通信",
            "云通信"
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信模板-可在短信控制台中找到
        $request->setTemplateCode("SMS_1000000");

        // 必填:模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
        // 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParamJson(json_encode(array(
            array(
                "name" => "Tom",
                "code" => "123",
            ),
            array(
                "name" => "Jack",
                "code" => "456",
            ),
        ), JSON_UNESCAPED_UNICODE));

        // 可选-上行短信扩展码(扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段)
        // $request->setSmsUpExtendCodeJson("[\"90997\",\"90998\"]");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * 短信发送记录查询
     * @return stdClass
     */
    public static function querySendDetails()
    {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，短信接收号码
        $request->setPhoneNumber("12345678901");

        // 必填，短信发送日期，格式Ymd，支持近30天记录查询
        $request->setSendDate("20170718");

        // 必填，分页大小
        $request->setPageSize(10);

        // 必填，当前页码
        $request->setCurrentPage(1);

        // 选填，短信发送流水号
        $request->setBizId("yourBizId");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

}

// 调用示例：
set_time_limit(0);
header('Content-Type: text/plain; charset=utf-8');

$response = SmsDemo::sendSms();
echo "发送短信(sendSms)接口返回的结果:\n";
print_r($response);

sleep(2);

$response = SmsDemo::sendBatchSms();
echo "批量发送短信(sendBatchSms)接口返回的结果:\n";
print_r($response);

sleep(2);

$response = SmsDemo::querySendDetails();
echo "查询短信发送情况(querySendDetails)接口返回的结果:\n";
print_r($response);
```

```php
<?php
require_once __DIR__ . '/lib/TokenGetterForAlicom.php';
require_once __DIR__ . '/lib/TokenForAlicom.php';

use Aliyun\DayuSDK\Core\Config;
use Aliyun\DayuSDK\MNS\Exception\MnsException;
use Aliyun\DayuSDK\MNS\Requests\BatchReceiveMessageRequest; // 批量拉取请求

// 加载区域结点配置
Config::load();

/**
 * Class MsgDemo
 */
class MsgDemo
{

    /**
     * @var TokenGetterForAlicom
     */
    static $tokenGetter = null;

    public static function getTokenGetter()
    {

        $accountId = "1943695596114318"; // 此处不需要替换修改!

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)

        $accessKeyId = "yourAccessKeyId"; // AccessKeyId

        $accessKeySecret = "yourAccessKeySecret"; // AccessKeySecret

        if (static::$tokenGetter == null) {
            static::$tokenGetter = new TokenGetterForAlicom(
                $accountId,
                $accessKeyId,
                $accessKeySecret);
        }
        return static::$tokenGetter;
    }

    /**
     * 获取消息
     *
     * @param string $messageType 消息类型
     * @param string $queueName 在云通信页面开通相应业务消息后，就能在页面上获得对应的queueName<br/>(e.g. Alicom-Queue-xxxxxx-xxxxxReport)
     * @param callable $callback <p>
     * 回调仅接受一个消息参数;
     * <br/>回调返回true，则工具类自动删除已拉取的消息;
     * <br/>回调返回false,消息不删除可以下次获取.
     * <br/>(e.g. function ($message) { return true; }
     * </p>
     */
    public static function receiveMsg($messageType, $queueName, callable $callback)
    {
        $i = 0;
        // 取回执消息失败3次则停止循环拉取
        while ($i < 3) {
            try {
                // 取临时token
                $tokenForAlicom = static::getTokenGetter()->getTokenByMessageType($messageType, $queueName);

                // 使用MNSClient得到Queue
                $queue = $tokenForAlicom->getClient()->getQueueRef($queueName);

                // ------------------------------------------------------------------
                // 1. 单次接收消息，并根据实际情况设置超时时间
                $message = $queue->receiveMessage(2);

                // 计算消息体的摘要用作校验
                $bodyMD5 = strtoupper(md5(base64_encode($message->getMessageBody())));

                // 比对摘要，防止消息被截断或发生错误
                if ($bodyMD5 == $message->getMessageBodyMD5()) {
                    // 执行回调
                    if (call_user_func($callback, json_decode($message->getMessageBody()))) {
                        // 当回调返回真值时，删除已接收的信息
                        $receiptHandle = $message->getReceiptHandle();
                        $queue->deleteMessage($receiptHandle);
                    }
                }
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // 2. 批量接收消息
                // $res = $queue->batchReceiveMessage(new BatchReceiveMessageRequest(10, 5)); // 每次拉取10条，超时等待时间5秒

                // /* @var \Aliyun\DayuSDK\MNS\Model\Message[] $messages */
                // $messages = $res->getMessages();

                // foreach($messages as $message) {
                //     // 计算消息体的摘要用作校验
                //     $bodyMD5 = strtoupper(md5(base64_encode($message->getMessageBody())));

                //     // 比对摘要，防止消息被截断或发生错误
                //     if ($bodyMD5 == $message->getMessageBodyMD5())
                //     {
                //         // 执行回调
                //         if(call_user_func($callback, json_decode($message->getMessageBody())))
                //         {
                //             // 当回调返回真值时，删除已接收的信息
                //             $receiptHandle = $message->getReceiptHandle();
                //             $queue->deleteMessage($receiptHandle);
                //         }
                //     }
                // }
                // ------------------------------------------------------------------

                return; // 整个取回执消息流程完成后退出
            } catch (MnsException $e) {
                $i++;
                echo "ex:{$e->getMnsErrorCode()}\n";
                echo "ReceiveMessage Failed: {$e}\n";
            }
        }
    }
}

// 调用示例：

header('Content-Type: text/plain; charset=utf-8');

echo "消息接口查阅短信状态报告返回结果:\n";
MsgDemo::receiveMsg(
// 消息类型，SmsReport: 短信状态报告
    "SmsReport",

    // 在云通信页面开通相应业务消息后，就能在页面上获得对应的queueName
    "Alicom-Queue-xxxxxxxx-SmsReport",

    /**
     * 回调
     * @param stdClass $message 消息数据
     * @return bool 返回true，则工具类自动删除已拉取的消息。返回false，消息不删除可以下次获取
     */
    function ($message) {
        print_r($message);
        return false;
    }
);


echo "消息接口查阅短信服务上行返回结果:\n";
MsgDemo::receiveMsg(
// 消息类型，SmsUp: 短信服务上行
    "SmsUp",

    // 在云通信页面开通相应业务消息后，就能在页面上获得对应的queueName
    "Alicom-Queue-xxxxxxxx-SmsUp",

    /**
     * 回调
     * @param stdClass $message 消息数据
     * @return bool 返回true，则工具类自动删除已拉取的消息。返回false，消息不删除可以下次获取
     */
    function ($message) {
        print_r($message);
        return false;
    }
);
```
