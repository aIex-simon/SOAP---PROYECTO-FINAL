<?php

namespace App\Models\Fin;

use App\Models\Bm\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchPaymentMethod extends Model
{
    protected $table = 'fin_branch_payment_methods';
    protected $primaryKey = ['branch_id', 'payment_method_id'];
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
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
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id')
            ->whereNull('deleted_at');
    }

    /**
     * paymentMethod
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id')
            ->whereNull('deleted_at');
    }
}
