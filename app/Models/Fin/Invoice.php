<?php

namespace App\Models\Fin;

use App\Models\Sal\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Invoice extends Model
{
    //use Searchable;

    const QUOTE = 1;
    const SALE_GUIDE = 5;
    const SALE_ADVANCE = 16;
    const SALE_ORDER = 17;

    protected $table = 'fin_sale_invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'standard_tax_document_tumisoft_id',
        'series',
        'correlative',
        'sale_id',
        'sale_point_id',
        'posting_date',
        'due_date',
        'comment',
        'status',
    ];

    /**
     * @var string[]
     */
    public static $indexRules = [
        'size' => 'nullable|integer|between:5,100',
        'page' => 'nullable|integer|min:0',
        'search' => 'string|max: 25',
    ];

    /**
     * @var string[]
     */
    public static $showRules = [
        'invoice' => 'required|exists:App\Models\Fin\Invoice,id',
    ];

    /**
     * @var string[]
     */
    public static $messageRules = [
        'required' => 'The :attribute is required.',
        'max' => 'The :attribute is very long.',
        'unique' => 'The :attribute has already been taken.',
        'exists' => 'Could not find :attribute',
        'latitude.between' => 'The latitude must be in range between -90 and 90',
        'longitude.between' => 'The longitude mus be in range between -180 and 180'
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
        return 'sale-invoices';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
       return $this->with(['sender', 'standardTaxDocumentTumisoft', 'sale', 'salePoint'])
                   ->where('id', '=', $this->id)
                   ->first()
                   ->toArray();
    }

    /**
     * sender
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class, 'sender_id');
    }

    /**
     * standardTaxDocumentTumisoft
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function standardTaxDocumentTumisoft(): BelongsTo
    {
        return $this->belongsTo(StandardTaxDocumentTumiSoft::class, 'standard_tax_document_tumisoft_id');
    }

    /**
     * sale
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id')->with(['client', 'details', 'payments']);
    }

    /**
     * salePoint
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function salePoint(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id');
    }
}
