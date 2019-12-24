<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/20
 * Time: 13:16
 */

namespace Chuwei\Cardpay\Controller;

class Notify extends Controller
{
    public function vendor(array $params = [])
    {
        $params = array_merge($params,request()->all());
        $params['backParam'] = base64_decode($params['backParam']);
        $params['failMsg'] = base64_decode($params['failMsg']);
        $params['returnMessage'] = base64_decode($params['returnMessage']);

        if($this->verifySignature($params)){
            return $params;
        }else{
            return false;
        }

    }
}
