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
        $refundOrderId = $this->buildOrder();
        $params = array_merge($params,[
            'service' => 'EntOrderDetailRefund',
            'refundOrderId' => $refundOrderId,
            'orderId' => $params['orderId'],
            'refundTolAmount' => $params['amount'],
        ]);

        return array_merge($this->request($params),[
            'refundOrderId' => $refundOrderId,
        ]);
    }

}
