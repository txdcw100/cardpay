<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/19
 * Time: 17:41
 */

namespace Chuwei\Cardpay\Controller;

class CashMove extends Controller
{

    public function vendor(array $params)
    {

        $params = array_merge($params,[
            'service' => 'merchantAcctTransfer',
			'subMerchantId' => $this->config['sub_merchant_id'] ?? '',
			'transferDirection' => '0',
            'transferDesc' => '退款金额',
        ]);

        $respones = $this->request($params);

        return $respones;
    }
}
