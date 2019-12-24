<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/10
 * Time: 9:39
 */

namespace Chuwei\Cardpay;

use Chuwei\Cardpay\Controller\Controller;

class Lakala extends Controller
{
    public function vendor(array $params = [])
    {
        return 'lakala';
    }
}
