<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/20
 * Time: 11:43
 */

namespace Chuwei\Cardpay\Controller;

class Refund extends Controller
{
    public function vendor(array $params)
    {
        $detail = [
            [
                'detailOrderId' => $params['translog_serial'].'S',
                'detailRefundAmount' => (string)$params['amount'],
                'rcvMerchantId' => $this->config['sub_merchant_id'],
            ]
        ];
        return $this->request([
            'service' => 'EntOrderDetailRefund',
            'refundOrderId' => $params['serial'],
            'orderId' => $params['translog_serial'],
            'refundTolAmount' => (string)$params['amount'],
            'refundDetail' => json_encode($detail, JSON_UNESCAPED_UNICODE)
        ]);
    }

    public function check(array $params){

        $params = [
            'service' => 'RefundDetailSeach',
            'refundOrderId' => $params['orderId']
        ];

        return $this->request($params);
    }

}
