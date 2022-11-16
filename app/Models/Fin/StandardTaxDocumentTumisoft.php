<?php

namespace App\Models\Fin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StandardTaxDocumentTumisoft extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    const ID_QUOTE = 1; // cotización
    const ID_SALE_NOTE = 2; // nota de venta
    const ID_TICKET = 3; // Boleta
    const ID_INVOICE = 4; // Factura
    const ID_PURCHASE_QUOTE = 8; // Cotizacion de compra
    const ID_PURCHASE_GUIDE = 9; // Guia de compra
    const ID_PURCHASE_ORDER = 10; // Orden de compra
    const ID_PURCHASE_ADVANCE = 11; // Anticipo de compra
    const ID_PURCHASE = 12; // Compra
    const ID_PURCHASE_RETURN = 13; // Devolucion de Compra
    const ID_SALE_ADVANCE = 16; // Anticipo de venta

    protected $table = 'fin_standard_tax_document_tumisoft';

    protected $dates = ['deleted_at'];
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'standard_tax_document_id',
        'name',
        'default_series',
        'default_correlative',
        'status',
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
