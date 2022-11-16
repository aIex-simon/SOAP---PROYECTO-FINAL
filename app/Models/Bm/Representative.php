<?php

namespace App\Models\Bm;

use App\Models\Fin\IdentityDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Representative extends Model
{
    use SoftDeletes;
    use Notifiable;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = 'bm_representatives';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'enterprise_id',
        'identity_document_type_id',
        'identity_document_number',
        'name',
        'lastname',
        'email',
        'phone',
        'status',
        'type',
        'address',
        'city',
        'region',
        'country',
        'zip_code'
    ];

    /**
     * identityDocumentType
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function identityDocumentType(): BelongsTo
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id')
            ->select(['id', 'code', 'name', 'abbreviation', 'status'])
            ->whereNull('deleted_at');
    }
}
