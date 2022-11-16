<?php

namespace App\Models\Fin;

use Illuminate\Database\Eloquent\Model;

class IdentityDocumentType extends Model
{
    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = 'fin_identity_document_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status'
    ];
}
