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

class MercUpdate extends Controller
{
    use AesTrait,MercTrait;

    public function vendor(array $params,LakalaConfig $config = null)
    {
        if(!$config['sub_merchant_id']){
            throw new \Exception('miss sub_merchant_id');
        }

        $params = array_merge($params,$this->mercInfo($config));
        $params = array_merge($params,[
            'service' => 'UpdateMercInfo',
            'outMerchantId' => $config['sub_merchant_id'],
        ]);

        list($respones,$backParams) = $this->request($params,true);

		$config->request_id = $backParams['requestId'] ?? '';
		$config->save();

        return [$respones,$backParams];
    }
}
