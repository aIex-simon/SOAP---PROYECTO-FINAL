<?php

namespace App\Models\Fin;

use App\Models\Bm\Enterprise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'fin_currencies';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numerical_code',
        'name',
        'entity_name',
        'minor_unity',
        'symbol',
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
        'currency' => 'required|exists:App\Models\Fin\Currency,id,deleted_at,NULL',
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
        return $this->hasMany(Enterprise::class);
    }
}
