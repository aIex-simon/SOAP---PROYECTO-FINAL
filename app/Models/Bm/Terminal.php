<?php

namespace App\Models\Bm;

use App\Models\Fin\SalePoint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terminal extends Model
{
    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = 'bm_terminals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'terminal_type_id',
        'branch_id',
        'employee_id',
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
        'terminal' => 'required|exists:App\Models\Bm\Terminal,id,deleted_at,NULL',
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
     * @param int $enterprise_id
     * @return Builder[]|Collection
     */
    public static function getTerminalsEnabledByBranch(int $branch_id)
    {
        return self::query()
            ->where(
                'status',
                '=',
                self::ACTIVE
            )
            ->where(
                'branch_id',
                '=',
                $branch_id
            )
            ->get();
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * branches
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function branches(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id')->whereNull('deleted_at');
    }

    /**
     * terminalType
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function terminalType(): BelongsTo
    {
        return $this->belongsTo(TerminalType::class, 'terminal_type_id')->whereNull('deleted_at');
    }

    /**
     * employee
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id')->whereNull('deleted_at');
    }

    /**
     * salePoint
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function salePoint(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id')
            ->with(['paymentMethods', 'series'])
            ->whereNull('deleted_at');
    }
}
