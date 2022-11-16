<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    public const FLAG_INITIAL_CONFIG_UNCONFIRMED = 0;
    public const FLAG_INITIAL_CONFIG_CONFIRMED = 1;

    protected $table = 'bm_warehouses';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id',
        'name',
        'phone',
        'address',
        'status',
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
     * branch
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id')->whereNull('deleted_at');
    }
}
