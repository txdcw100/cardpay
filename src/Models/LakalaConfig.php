<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/17
 * Time: 17:06
 */

namespace Chuwei\Cardpay\Models;

use Illuminate\Database\Eloquent\Model;

class LakalaConfig extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('lakala.table_names.configs'));
    }
}
