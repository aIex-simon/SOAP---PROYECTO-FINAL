<?php

namespace App\Models\Im;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTerminal extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'im_product_terminals';
    protected $primaryKey = ['product_id', 'terminal_id'];
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'terminal_id',
        'tribute_type_id',
        'stock_available',
        'stock_real',
        'stock_reserved',
        'price_cost',
        'price_sale',
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
        'product' => 'required|exists:App\Models\Im\Product,id,deleted_at,NULL',
    ];

    /**
     * @var string[]
     */
    public static $storeRules = [
        'product_id' => 'required|exists:App\Models\Im\Product,id,deleted_at,NULL',
        'terminal_id' => 'required|exists:App\Models\Bm\Terminal,id,deleted_at,NULL',
        'tribute_type_id' => 'required|exists:App\Models\Fin\TributeType,id,deleted_at,NULL',
        'stock_available' => 'required|nullable|numeric',
        'stock_real' => 'required|nullable|numeric',
        'stock_reserved' => 'required|nullable|numeric',
        'price_cost' => 'required|numeric',
        'price_sale' => 'required|numeric',
        'status' => 'required|integer|between:0,1',
    ];

    /**
     * @var string[]
     */
    public static $updatePutRules = [
        'product_id' => 'required|exists:App\Models\Im\Product,id,deleted_at,NULL',
        'terminal_id' => 'required|exists:App\Models\Bm\Terminal,id,deleted_at,NULL',
        'tribute_type_id' => 'required|exists:App\Models\Fin\TributeType,id,deleted_at,NULL',
        'stock_available' => 'required|nullable|numeric',
        'stock_real' => 'required|nullable|numeric',
        'stock_reserved' => 'required|nullable|numeric',
        'price_cost' => 'required|numeric',
        'price_sale' => 'required|numeric',
        'status' => 'required|integer|between:0,1',
    ];

    /**
     * @var string[]
     */
    public static $updatePatchRules = [
        'product_id' => 'required|exists:App\Models\Im\Product,id,deleted_at,NULL',
        'terminal_id' => 'required|exists:App\Models\Bm\Terminal,id,deleted_at,NULL',
        'tribute_type_id' => 'required|exists:App\Models\Fin\TributeType,id,deleted_at,NULL',
        'stock_available' => 'nullable|numeric',
        'stock_real' => 'nullable|numeric',
        'stock_reserved' => 'nullable|numeric',
        'price_cost' => 'numeric',
        'price_sale' => 'numeric',
        'status' => 'integer|between:0,1',
    ];

    /**
     * @var string[]
     */
    public static $messageRules = [
        'required' => 'The :attribute is required.',
        'max' => 'The :attribute is very long.',
        'unique' => 'The :attribute has already been taken.',
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * product
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')
            ->whereNull('deleted_at');
    }

    /**
     * terminal
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function terminal(): BelongsTo
    {
        return $this->belongsTo(ProductTerminal::class, 'terminal_id')
            ->whereNull('deleted_at');
    }
}
