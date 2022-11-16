<?php

namespace App\Models\Fin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Series extends Model
{
    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = 'fin_series';

    protected $primaryKey = ['sender_id', 'standard_tax_document_tumisoft_id', 'series'];
    public $incrementing = false;
    protected $keyType = 'array';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'standard_tax_document_tumisoft_id',
        'series',
        'correlative_initial',
        'sale_point_id',
        'status'
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
        'series' => 'required|exists:App\Models\Fin\Series,series,deleted_at,NULL',
    ];

    /**
     * @var string[]
     */
    public static $storeRules = [
        'standard_tax_document_tumisoft_id' => 'required|max:14|exists:App\Models\Fin\StandardTaxDocumentTumisoft,id,deleted_at,NULL',
        'correlative_initial' => 'required|int|max:11',
    ];

    /**
     * @var string[]
     */
    public static $updatePutRules = [
        'correlative_initial' => 'required|int|max:11',
        'status' => 'required|int|max:1'
    ];

    /**
     * @var string[]
     */
    public static $updatePatchRules = [
        'correlative_initial' => 'int|max:11',
        'status' => 'int|max:1'
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
     * sender
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class, 'sender_id')->whereNull('deleted_at');
    }

    /**
     * StandardTaxDocumentTumisoft
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function standardTaxDocumentTumisoft(): BelongsTo
    {
        return $this->belongsTo(StandardTaxDocumentTumisoft::class, 'standard_tax_document_tumisoft_id')->whereNull('deleted_at');
    }

    /**
     * salePoint
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function salePoint(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id')->whereNull('deleted_at');
    }
}
