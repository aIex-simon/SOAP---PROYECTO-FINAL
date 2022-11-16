<?php

namespace App\Models\Fin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;
    const KUSHKI = 20;

    protected $table = 'fin_payment_methods';

    protected $dates = ['deleted_at'];
}
