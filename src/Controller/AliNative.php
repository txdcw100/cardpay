<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/27
 * Time: 16:49
 */

namespace Chuwei\Cardpay\Controller;

use Chuwei\Cardpay\Traits\OrderTrait;

class AliNative extends Controller
{
    use OrderTrait;

    public function vendor(array $params = [])
    {
        // TODO: Implement vendor() method.
        $response = $this->payment($params['orderId'],$params['amount'],$params['goodName']);

        if (!isset($response['token'])) {
            throw new \Exception('token error: '.json_encode($response));
        }

        return $this->pay($params['orderId'],$response['token'],self::PAYCHLTYP_ALIPAY,self::TRADE_TYPE_ALIPAY_NATIVE);

    }
}
