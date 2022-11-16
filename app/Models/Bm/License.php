<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;
    const FREEMIUM_YES = 1;
    const FREEMIUM_NOT = 0;

    protected $table = 'bm_licenses';
    protected $dates = ['deleted_at'];

    protected $primaryKey = 'tumisoft_product_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tumisoft_product_id',
        'periodicity',
        'flag_cpe_limit',
        'number_cpe_month_max',
        'number_enterprises_max',
        'flag_sales_limit',
        'flag_users_limit',
        'number_stores_max',
        'flag_web_version',
        'flag_all_appstores',
        'flag_unlimited_products',
        'flag_reports',
        'flag_peripherals_integrations',
        'flag_freemium',
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
        'license' => 'required|exists:App\Models\Bm\License,tumisoft_product_id,deleted_at,NULL',
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
     * @return HasOne
     */
    public function tumisoftProduct()
    {
        return $this->hasOne(TumisoftProduct::class, 'id', 'tumisoft_product_id');
    }
}
