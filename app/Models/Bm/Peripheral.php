<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peripheral extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'bm_peripherals';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'peripheral_category_id',
        'enterprise_id',
        'name',
        'description',
        'barcode',
        'ip',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
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
        'peripheral' => 'required|exists:App\Models\Bm\Peripheral,id,deleted_at,NULL',
    ];

    /**
     * @var string[]
     */
    public static $messageRules = [
        'required' => 'The :attribute is required.',
        'max' => 'The :attribute is very long.',
        'unique' => 'The :attribute has already been taken.',
        'exists' => 'Could not find :attribute'
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * category
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PeripheralCategory::class, 'peripheral_category_id')
            ->select(['tumisoft_product_id', 'description', 'status'])
            ->whereNull('deleted_at');
    }

    /**
     * terminal
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id')
            ->select(['id', 'name', 'sender_id', 'contact_phone', 'address', 'status'])
            ->whereNull('deleted_at');
    }
}
