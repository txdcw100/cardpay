<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/18
 * Time: 9:45
 */

namespace Chuwei\Cardpay\Controller;

use Chuwei\Cardpay\Models\LakalaConfig;
use Chuwei\Cardpay\Traits\AesTrait;
use Chuwei\Cardpay\Traits\MercTrait;

class MercRegister extends Controller
{
    use AesTrait,MercTrait;

    public function vendor(array $params,LakalaConfig $config = null)
    {
        if($config['sub_merchant_id']){
            throw new \Exception('has sub_merchant_id');
        }

        $params = array_merge($params,$this->mercInfo($config));
        $params = array_merge($params,[
            'service' => 'MercRegister',
        ]);

        list($respones,$backParams) = $this->request($params,true);

		$config->sub_merchant_id = $respones['outMerchantId'] ?? '';
		$config->request_id = $backParams['requestId'] ?? '';
		$config->save();

        return [$respones,$backParams];
    }
}
