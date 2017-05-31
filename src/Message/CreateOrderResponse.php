<?php

namespace Omnipay\AyPay\Message;

use Omnipay\AyPay\Helper;

/**
 * Class CreateOrderResponse
 * @package Omnipay\AyPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_1
 */
class CreateOrderResponse extends BaseAbstractResponse
{

    /**
     * @var CreateOrderRequest
     */
    protected $request;

    public function getPayInfo()
    {
        if ($this->isSuccessful()) {
            $data = $this->getData();
            $data = str_replace("{xml}", "&", $data['pay_info']);
        } else {
            $data = null;
        }

        return $data;
    }
}