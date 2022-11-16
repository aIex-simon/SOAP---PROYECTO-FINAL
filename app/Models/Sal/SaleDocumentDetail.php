<?php

namespace App\Models\Sal;

use Illuminate\Database\Eloquent\Model;

class SaleDocumentDetail extends Model
{
    protected $table = 'sal_sale_document_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale_id',
        'sale_document_id',
        'product_id',
        'product_category_id',
        'commercial_measure_unit_id',
        'tribute_type_id',
        'discount',
        'name',
        'description',
        'price',
        'quantity',
        'weight',
    ];
}
