<?php

namespace App\Models\Sal;

use App\Models\Fin\Payment;
use App\Models\Fin\PaymentMethod;
use App\Models\Im\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDocument extends Model
{
    use SoftDeletes;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = 'sal_sale_documents';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'currency_id',
        'client_id',
        'terminal_id',
        'total',
        'subtotal',
        'discount',
        'exchange',
        'status',
        'ip',
        'operative_system',
        'browser',
        'latitude',
        'longitude',
        'internet_speed',
        'provider_isp',
    ];

    /**
     * products
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, SaleDocumentDetail::class, 'sale_id');
    }

    /**
     * saleDetails
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(SaleDocumentDetail::class, 'sale_id');
    }

    /**
     * paymentMethods
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsToMany
     */
    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, Payment::class, 'sale_id');
    }

    /**
     * payments
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'sale_id');
    }

    /**
     * client
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }
}
