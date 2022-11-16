<?php

namespace App\Models\Sal;

use App\Models\Bm\Location;
use App\Models\Fin\Contributor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Client extends Model
{
    use SoftDeletes;
    use Searchable;

    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'sal_clients';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contributor_id',
        'location_id',
        'enterprise_id',
        'code',
        'name',
        'lastname',
        'phone',
        'email',
        'business_reason',
        'business_name',
        'contact_name',
        'address',
        'note',
        'birthday',
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
        'client' => 'required|exists:App\Models\Sal\Client,id,deleted_at,NULL',
    ];

    /**
     * @var string[]
     */
    public static $updatePutRules = [
        'client' => 'required|exists:App\Models\Sal\Client,id,deleted_at,NULL',
        'contributor_id' => 'required|max:14|exists:App\Models\Fin\Contributor,id,deleted_at,NULL',
        'identity_document_type_id' => 'required|exists:App\Models\Fin\IdentityDocumentType,id,deleted_at,NULL',
        'location_id' => 'nullable|exists:App\Models\Bm\Location,id,deleted_at,NULL',
        //'code' => 'required|max:20',
        'name' => 'nullable|max:45',
        'lastname' => 'nullable|max:80',
        'phone' => 'nullable|max:15',
        'email' => 'nullable|max:80|email:rfc,dns',
        'business_reason' => 'nullable|max:80',
        'business_name' => 'nullable|max:80',
        'contact_name' => 'nullable|string|max:80',
        'address' => 'nullable|string|max:100',
        'birthday' => 'nullable|date_format:Y-m-d|before:today',
        'client_alternative_directions' => 'array',
        'client_alternative_directions.*.name' => 'string|max:255',
        'client_alternative_directions.*.location_id' => 'exists:App\Models\Bm\Location,id,deleted_at,NULL',
        'client_alternative_directions.*.address' => 'string|max:255',
    ];

    /**
     * @var string[]
     */
    public static $updatePatchRules = [
        'client' => 'required|exists:App\Models\Sal\Client,id,deleted_at,NULL',
        'contributor_id' => 'nullable|max:14|exists:App\Models\Fin\Contributor,id,deleted_at,NULL',
        'identity_document_type_id' => 'nullable|exists:App\Models\Fin\IdentityDocumentType,id,deleted_at,NULL',
        'location_id' => 'nullable|exists:App\Models\Bm\Location,id,deleted_at,NULL',
        //'code' => 'nullable|max:20',
        'name' => 'nullable|max:45',
        'lastname' => 'nullable|max:80',
        'phone' => 'nullable|max:15',
        'email' => 'nullable|max:80|email:rfc,dns',
        'business_reason' => 'nullable|max:80',
        'business_name' => 'nullable|max:80',
        'contact_name' => 'nullable|string|max:80',
        'address' => 'nullable|string|max:100',
        'birthday' => 'nullable|date_format:Y-m-d|before:today',
        'client_alternative_directions' => 'array',
        'client_alternative_directions.*.id' => 'nullable|exists:App\Models\Sal\ClientAlternativeDirection,id,deleted_at,NULL',
        'client_alternative_directions.*.name' => 'string|max:255',
        'client_alternative_directions.*.location_id' => 'exists:App\Models\Bm\Location,id,deleted_at,NULL',
        'client_alternative_directions.*.address' => 'string|max:255',
    ];

    /**
     * @var string[]
     */
    public static $messageRules = [
        'required' => 'The :attribute is required.',
        'max' => 'The :attribute is very long.',
        'unique' => 'The :attribute has already been taken.',
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
        return 'clients';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->with(['contributor', 'location', 'clientAlternativeDirections'])
            ->where('id', '=', $this->id)
            ->first()
            ->toArray();
    }

    /**
     * @return HasMany
     */
    public function clientAlternativeDirections(): HasMany
    {
        return $this->hasMany(ClientAlternativeDirection::class, 'client_id')->with(['location'])
            ->whereNull('deleted_at');
    }

    /**
     * location
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id')
            ->with(['parent'])
            ->whereNull('deleted_at');
    }

    /**
     * contributor
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return BelongsTo
     */
    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class, 'contributor_id')->whereNull('deleted_at');
    }
}
