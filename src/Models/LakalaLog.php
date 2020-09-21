<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/17
 * Time: 17:15
 */

namespace Chuwei\Cardpay\Models;

use Illuminate\Database\Eloquent\Model;

class LakalaLog extends Model
{
	protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('lakala.table_names.logs'));
    }
}
