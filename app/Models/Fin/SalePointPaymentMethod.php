<?php

namespace App\Models\Fin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePointPaymentMethod extends Model
{
    protected $table = 'fin_sale_point_payment_methods';
    protected $primaryKey = ['sale_point_id', 'payment_method_id'];
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale_point_id',
        'payment_method_id',
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * salePoint
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function salePoint(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id')
            ->whereNull('deleted_at');
    }

    /**
     * paymentMethod
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id')
            ->whereNull('deleted_at');
    }
}
