<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/19
 * Time: 17:41
 */

namespace Chuwei\Cardpay\Controller;

use Chuwei\Cardpay\Models\LakalaConfig;

class CashCheck extends Controller
{

    public function vendor(array $params)
    {

        $params = array_merge($params,[
            'service' => 'QuerySubMercBal',
        ]);

        $respones = $this->request($params);

        return $respones;
    }
}
