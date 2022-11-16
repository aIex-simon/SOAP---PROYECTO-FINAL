<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInfrastructureSubscription extends Model
{
    use SoftDeletes;

    protected $table = 'bm_payment_infrastructure_subscriptions';

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
        'enterprise_id',
        'payment_infrastructure_id',
        'subscription_id',
    ];

    /**
     * payments
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_infrastructure_subscription_id', 'id')
            //->select('charge_code', 'payment_infrastructure_status_id', 'status', 'created_at', 'updated_at', 'deleted_at')
            ->whereNull('deleted_at');
    }
}
