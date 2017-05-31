<?php

namespace Omnipay\AyPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\AyPay\Helper;

/**
 * Class CreateOrderRequest
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_1
 * @method CreateOrderResponse send()
 */
class CreateOrderRequest extends BaseAbstractRequest
{

    protected $endpoint = 'https://vip.iyibank.com/pay/gateway';


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate(
            'mch_id',
            'body',
            'out_trade_no',
            'total_fee',
            'notify_url',
            'service',
            'mch_create_ip'
        );

        $service = strtoupper($this->getService());

        if ($service == 'cibwxh5' || $service == 'citiwxh5') {
            $this->validate('open_id');
        }

        $data = array(
            'service' => $this->getService(), //*
            'version' => '1.0',
            'charset' => 'utf-8',
            'sign_type' => 'MD5',
            'mch_id' => $this->getMchId(),
            'out_trade_no' => $this->getOutTradeNo(),//*
            'device_info' => $this->getDeviceInfo(),
            'body' => $this->getBody(),//*
            'sub_openid' => $this->getOpenId(),//*(service=JSAPI)
            'attach' => $this->getAttach(),
            'total_fee' => $this->getTotalFee(),//*
            'mch_create_ip' => $this->getMchCreateIp(),//*
            'notify_url' => $this->getNotifyUrl(), //*
            'callback_url' => $this->getCallbackUrl(), //*
            'nonce_str' => md5(uniqid()),//*
        );

        $data = array_filter($data);

        $data['sign'] = Helper::sign($data, $this->getMchKey());

        return $data;
    }


    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->getParameter('service');
    }

    public function getCallbackUrl()
    {
        return $this->getParameter('callback_url');
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->getParameter('body');
    }

    /**
     * @return mixed
     */
    public function getAttach()
    {
        return $this->getParameter('attach');
    }

    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }

    /**
     * @return mixed
     */
    public function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }

    /**
     * @return mixed
     */
    public function getMchCreateIp()
    {
        return $this->getParameter('mch_create_ip');
    }

    /**
     * @return mixed
     */
    public function getDeviceInfo()
    {
        return $this->getParameter('device_info');
    }

    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    /**
     * @return mixed
     */
    public function getOpenId()
    {
        return $this->getParameter('open_id');
    }


    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->setParameter('body', $body);
    }

    /**
     * @param mixed $attach
     */
    public function setDeviceInfo($deviceInfo)
    {
        $this->setParameter('device_info', $deviceInfo);
    }

    /**
     * @param mixed $attach
     */
    public function setAttach($attach)
    {
        $this->setParameter('attach', $attach);
    }


    /**
     * @param mixed $outTradeNo
     */
    public function setOutTradeNo($outTradeNo)
    {
        $this->setParameter('out_trade_no', $outTradeNo);
    }


    /**
     * @param mixed $totalFee
     */
    public function setTotalFee($totalFee)
    {
        $this->setParameter('total_fee', $totalFee);
    }


    /**
     * @param mixed $spbillCreateIp
     */
    public function setMchCreateIp($mchCreateIp)
    {
        $this->setParameter('mch_create_ip', $mchCreateIp);
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->setParameter('service', $service);
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->setParameter('notify_url', $notifyUrl);
    }

    public function setCallbackUrl($callbackUrl)
    {
        $this->setParameter('callback_url', $callbackUrl);
    }

    /**
     * @param mixed $openId
     */
    public function setOpenId($openId)
    {
        $this->setParameter('open_id', $openId);
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $request = $this->httpClient->post($this->endpoint)->setBody(Helper::array2xml($data));
        $response = $request->send()->getBody();

        $responseData = Helper::xml2array($response);
        //var_dump($responseData);die;
        return $this->response = new CreateOrderResponse($this, $responseData);
    }
}
