<?php

namespace App\Models\Im;

use App\Models\Bm\Terminal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use SoftDeletes;
    use Searchable;

    public const ACTIVE = 1;
    public const INACTIVE = 0;
    public const DIRECTORY_IMAGE = 'product';

    protected $table = 'im_products';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'enterprise_id',
        'product_type_id',
        'product_category_id',
        'commercial_measure_unit_id',
        'name',
        'description',
        'code',
        'barcode',
        'price',
        'weight',
        'is_purchase',
        'is_sale',
        'is_storable',
        'image',
        'consumption_tax_plastic_bags_status',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var string[]
     */
    public static $indexRules = [
        'size' => 'nullable|integer|between:5,100',
        'page' => 'nullable|integer|min:0',
        'search' => 'string|max:25'
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
     * Get the name of the index for meili search associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'products';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->with(['productType', 'productCategory', 'productTerminals'])
            ->where('id', '=', $this->id)
            ->first()
            ->toArray();
    }

    /**
     * productTerminals
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function productTerminals(): HasMany
    {
        return $this->hasMany(ProductTerminal::class, 'product_id')
            ->where('status', ProductTerminal::ACTIVE);
    }

    /**
     * terminals
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsToMany
     */
    public function terminals(): BelongsToMany
    {
        return $this->belongsToMany(Terminal::class, ProductTerminal::class);
    }

    /**
     * productVariableValues
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return HasMany
     */
    public function productVariableValues(): HasMany
    {
        return $this->hasMany(ProductVariableValue::class, 'product_id');
    }

    /**
     * variableValues
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsToMany
     */
    public function variableValues(): BelongsToMany
    {
        return $this->belongsToMany(VariableValue::class, ProductVariableValue::class);
    }

    /**
     * productType
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id')
            ->select('id','name')
            ->whereNull('deleted_at');
    }

    /**
     * productCategory
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id')
            ->select('id','description', 'description_en')
            ->whereNull('deleted_at');
    }

    /**
     * commercialMeasureUnit
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function commercialMeasureUnit(): BelongsTo
    {
        return $this->belongsTo(CommercialMeasureUnit::class, 'commercial_measure_unit_id')
            ->select('id', 'name', 'category_code', 'symbol', 'conversion_factor')
            ->whereNull('deleted_at');
    }
}
