<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use SoftDeletes;
    use Notifiable;

    public const ACTIVE = 1;
    public const INACTIVE = 0;
    public const PAYMENT_ACCEPTED = 1;
    public const PAYMENT_DECLINED = 0;

    protected $table = 'bm_payments';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'tumisoft_product_id',
        'payment_infrastructure_subscription_id',
        'amount',
        'charge_code',
        'payment_infrastructure_status_id',
        'charge_response',
        'status',
    ];

    protected $casts = [
        'charge_response' => 'array',
    ];

    /**
     * tumisoftProduct
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return HasMany
     */
    public function tumisoftProduct(): BelongsTo
    {
        return $this->belongsTo(TumisoftProduct::class, 'tumisoft_product_id');
    }
}
