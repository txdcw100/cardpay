<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/17
 * Time: 9:32
 */

namespace Chuwei\Cardpay\Traits;


trait OrderTrait
{
    /**
     * 预下单
     * @param $orderId
     * @param $orderTime
     * @param $amount
     * @param $body
     * @param $redirectUrl
     * @param $backParam
     * @return array
     */
    public function payment($orderId, $amount, $body, $redirectUrl = '',  $backParam = '')
    {
        return $this->makeOrder('EntOffLinePayment', ...func_get_args());
    }


    /**
     * 预下单接口（B2B&B2C）
     */

    /**
     * 预下单(快捷模式)
     */

    /**
     * 下单
     * @param $service
     * @param $orderId
     * @param $amount
     * @param $body
     * @param $redirectUrl
     * @param $backParam
     * @return array
     */
    public function makeOrder($service, $orderId, $amount, $body, $redirectUrl = '', $backParam = '')
    {
        $orderDetail = [
            [
                'orderSeqNo' => '001',
                'detailOrderId' => $orderId.'S',
                'orderAmt' => '' . $amount,
                'shareFee' => '0',
                'rcvMerchantId' => $this->config['sub_merchant_id'] ?? $this->config['merchant_id'],
                'rcvMerchantIdName' => '',
                'productName' => $body,
                'productId' => '',
                'productDesc' => '',
                'showUrl' => '',
            ]
        ];
        $params = [
            'service' => $service,
            'offlineNotifyUrl' => $this->config['notify_url'],
            'pageNotifyUrl' => $this->config['page_notify_url'],
            'clientIP' => $this->getClientIP(),
            'orderId' => $orderId,
            'orderTime' => date('YmdHis'),
            'totalAmount' => '' . $amount,
            'currency' => 'CNY',
            'splitType' => '1',
            'validUnit' => '00',
            'validNum' => '15',
            'orderDetail' => json_encode($orderDetail, JSON_UNESCAPED_UNICODE)
        ];
        if ($backParam) {
            $params['backParam'] = $backParam;
        }

        return $this->request($params);

    }

    /**
     * 微信/支付宝支付
     * @param $orderId
     * @param $token
     * @param $payChlTyp
     * @param $tradeType
     * @param string $appId
     * @param string $openId
     * @return array
     */
    public function pay($orderId, $token, $payChlTyp, $tradeType, $appId = '', $openId = '')
    {
        $params = [
            'service' => 'QRCodePaymentCommit',
            'clientIP' => $this->getClientIP(),
            'orderId' => $orderId,
            'creDt' => date('Ymd'),
            'token' => $token,
            'payChlTyp' => $payChlTyp,
            'tradeType' => $tradeType,
            'appId' => $appId,
            'openId' => $openId
        ];

        return $this->request($params);
    }

}
