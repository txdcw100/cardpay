<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/17
 * Time: 9:33
 */

namespace Chuwei\Cardpay\Controller;


class QueryOrder extends Controller
{
    public function vendor(array $params = [])
    {
        // TODO: Implement vendor() method.
        $params = [
            'service' => 'EntMergeOrderSearch',
            'orderId' => $params['orderId']
        ];

        return $this->request($params);
    }

}
