<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2020/3/12
 * Time: 9:16
 */

namespace Chuwei\Cardpay\Controller;


class Statement extends Controller
{
    public function vendor(array $params){
        return $this->request([
            'service' => 'EntStatementFile',
            'acDate' => $params['acDate'],
        ]);
    }
}
