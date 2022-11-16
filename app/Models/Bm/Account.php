<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use SoftDeletes;

    public const ACCOUNT_STATUS_ACTIVE = 1;
    public const ACCOUNT_STATUS_INACTIVE = 0;
    public const INITIAL_CONFIG_COMPLETE = 1;
    public const INITIAL_CONFIG_INCOMPLETE = 0;

    protected $table = 'bm_accounts';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'license_id',
        'name',
        'description',
        'holding_name',
        'flag_initial_config',
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
        'account' => 'required|exists:App\Models\Bm\Account,id,deleted_at,NULL',
    ];

    /**
     * @var string[]
     */
    public static $storeRules = [
        'license_id' => 'required|exists:App\Models\Bm\License,tumisoft_product_id,deleted_at,NULL',
        'name' => 'nullable|max:80',
        'description' => 'nullable|max:1000',
        'holding_name' => 'nullable|max:100'
    ];

    /**
     * @var string[]
     */
    public static $updatePutRules = [
        'account' => 'required|exists:App\Models\Bm\Account,id,deleted_at,NULL',
        'license_id' => 'required|exists:App\Models\Bm\License,tumisoft_product_id,deleted_at,NULL',
        'name' => 'required|max:80',
        'description' => 'nullable|max:1000',
        'holding_name' => 'nullable|max:100'
    ];

    /**
     * @var string[]
     */
    public static $updatePatchRules = [
        'account' => 'required|exists:App\Models\Bm\Account,id,deleted_at,NULL',
        'license_id' => 'nullable|exists:App\Models\Bm\License,tumisoft_product_id,deleted_at,NULL',
        'name' => 'nullable|max:80',
        'description' => 'nullable|max:1000',
        'holding_name' => 'nullable|max:100'
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
     * enterprises
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return HasMany
     */
    public function enterprises(): HasMany
    {
        return $this->hasMany(Enterprise::class)->whereNull('deleted_at');
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
