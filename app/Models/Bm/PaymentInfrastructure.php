<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;

class PaymentInfrastructure extends Model
{
    protected $table = 'bm_payment_infrastructures';

    public const ID_KUSHKI = 1;
    public const ID_PAGO_EFECTIVO = 2;
    public const ID_CULQI = 3;
}
