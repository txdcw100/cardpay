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
                'rcvMerchantId' => '872100003015000',
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

}
