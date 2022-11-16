<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInfrastructureStatus extends Model
{
    use SoftDeletes;

    public const KUSHKI_IN_PROCESS = 1;
    public const KUSHKI_SUCCESS_FULL_CHARGE = 2;
    public const KUSHKI_FAILED_RETRY = 3;
    public const KUSHKI_LAST_RETRY = 4;
    public const KUSHKI_DECLINED_CHARGE = 5;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = 'bm_payment_infrastructure_status';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'payment_infrastructure_id',
        'name',
        'description',
        'status'
    ];
}
