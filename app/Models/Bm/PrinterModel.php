<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrinterModel extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'bm_printer_models';
    protected $dates = ['deleted_at'];

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'printer_brand_id',
        'printer_sdk_type_id',
        'name',
        'paper_sheet_id',
        'paper_cut',
        'paper_size',
        'speed',
        'connection_keep',
        'dpi',
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
        'printerModel' => 'required|exists:App\Models\Bm\PrinterModel,id,deleted_at,NULL',
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
     * connectionTypes
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsToMany
     */
    public function connectionTypes(): BelongsToMany
    {
        return $this->belongsToMany(PrinterConnectionType::class, PrinterModelConnectionType::class);
    }

    /**
     * PrinterModelConnectionTypes
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return HasMany
     */
    public function PrinterModelConnectionTypes(): HasMany
    {
        return $this->hasMany(PrinterModelConnectionType::class, 'printer_model_id');
    }

    /**
     * brand
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(PrinterBrand::class, 'printer_brand_id')->whereNull('deleted_at');
    }

    /**
     * SDKType
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function sdkType(): BelongsTo
    {
        return $this->belongsTo(PrinterSDKType::class, 'printer_sdk_type_id')->whereNull('deleted_at');
    }

    /**
     * paperSheet
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function paperSheet(): BelongsTo
    {
        return $this->belongsTo(PrinterPaperSheetType::class, 'paper_sheet_id');
    }
}
