<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'bm_locations';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'code',
        'display_name',
        'search_name',
        'capital_id',
        'latitude',
        'longitude',
        'children',
        'status',
    ];

    /**
     * @var string[]
     */
    public static $indexRules = [
        'size' => 'nullable|integer|between:5,1000',
        'page' => 'nullable|integer|min:0',
        'parent_id' => 'nullable|exists:App\Models\Bm\Location,id,deleted_at,NULL',
    ];

    /**
     * @var string[]
     */
    public static $showRules = [
        'location' => 'required|exists:App\Models\Bm\Location,id,deleted_at,NULL',
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

    /**
     * parent
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id')->with(['parent'])->whereNull('deleted_at');
    }
}
