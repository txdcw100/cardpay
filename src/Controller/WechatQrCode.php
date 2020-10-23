<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/27
 * Time: 15:42
 */

namespace Chuwei\Cardpay\Controller;

use Chuwei\Cardpay\Traits\OrderTrait;

class WechatQrCode extends Controller
{
    use OrderTrait;

    public function vendor(array $params = [])
    {
        // TODO: Implement vendor() method.
        $response = $this->payment($params['orderId'],$params['amount'],$params['goodName']);

		return $response['h5JumpUrl'] ?? false;

    }
}
