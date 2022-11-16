<?php

namespace App\Models\Bm;

use App\Models\Fin\BranchPaymentMethod;
use App\Models\Fin\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    public const FLAG_INITIAL_CONFIG_UNCONFIRMED = 0;
    public const FLAG_INITIAL_CONFIG_CONFIRMED = 1;

    protected $table = 'bm_branches';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'enterprise_id',
        'name',
        'phone',
        'address',
        'status',
    ];

    /**
     * @var string[]
     */
    public static $indexRules = [
        'size' => 'nullable|integer|between:5,100',
        'page' => 'nullable|integer|min:0',
    ];

    /**
     * @var string[]
     */
    public static $showRules = [
        'branch' => 'required|exists:bm_branches,id',
    ];

    /**
     * @var string[]
     */
    public static $messageRules = [
        'required' => 'The :attribute is required.',
        'max' => 'The :attribute is very long.',
        'unique' => 'The :attribute has already been taken.',
        'exists' => 'Could not find :attribute',
    ];

    /**
     * get table name
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * enterprise
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id')->whereNull('deleted_at');
    }

    /**
     * warehouses
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'branch_id')->whereNull('deleted_at');
    }

    /**
     * terminals
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function terminals(): HasMany
    {
        return $this->hasMany(Terminal::class, 'branch_id')->whereNull('deleted_at');
    }

    /**
     * paymentMethods
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(BranchPaymentMethod::class, 'branch_id')->with('paymentMethod');
    }
}
