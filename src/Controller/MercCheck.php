<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/19
 * Time: 17:41
 */

namespace Chuwei\Cardpay\Controller;

use Chuwei\Cardpay\Models\LakalaConfig;
use Chuwei\Cardpay\Traits\AesTrait;
use Chuwei\Cardpay\Traits\MercTrait;

class MercCheck extends Controller
{
    use AesTrait,MercTrait;

    public function vendor(array $params,LakalaConfig $config = null)
    {

        $params = array_merge($params,[
            'service' => 'MercRegisterSerch',
            'reqTime' => date('YmdHis'),
            'orgiRequestId' => $config['request_id'],
        ]);

        $respones = $this->request($params);

        return $respones;
    }
}
