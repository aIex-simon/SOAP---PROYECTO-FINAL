<?php

namespace App\Models\Fin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sender extends Model
{
    use SoftDeletes;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'fin_senders';
    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'identity_document_type_id',
        'trade_name',
        'business_name',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
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
        'sender' => 'required|exists:App\Models\Fin\Contributor,id,deleted_at,NULL',
    ];

    /**
     * @var string[]
     */
    public static $storeRules = [
        'id' => 'required|max:14',
        'identity_document_type_id' => 'required|max:14|exists:App\Models\Fin\IdentityDocumentType,id,deleted_at,NULL',
        'trade_name' => 'required|string|max:100',
        'business_name' => 'required|string|max:100',
    ];

    /**
     * identityDocumentType
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function identityDocumentType(): BelongsTo
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id')->whereNull('deleted_at');
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
