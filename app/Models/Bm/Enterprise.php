<?php

namespace App\Models\Bm;

use App\Models\Fin\Currency;
use App\Models\Fin\Sender;
use App\Models\Fin\TaxAdministrationSystem;
use App\Traits\StorageDisk;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enterprise extends Model
{
    use SoftDeletes;
    use StorageDisk;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    public const TYPE_BRANCH_OFFICE = 1;
    public const TYPE_SUBSIDIARY = 2;
    public const TYPE_FILIAL = 3;

    public const DIRECTORY_IMAGE = 'enterprise';

    protected $table = 'bm_enterprises';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'license_assigned_on'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'account_id',
        'location_id',
        'currency_id',
        'license_id',
        'license_assigned_on',
        'tax_administration_system_id',
        'name',
        'contact_phone',
        'address',
        'decimal_number_amount',
        'decimal_number_price',
        'decimal_number_rate',
        'decimal_number_quantity',
        'decimal_number_percent',
        'decimal_number_unit',
        'economic_activity',
        'flag_electronic_billing',
        'tax_administration_system_user',
        'tax_administration_system_password',
        'tax_administration_system_status',
        'logo_menu',
        'logo_ticket',
        'logo_a4',
        'is_physical_address',
        'enterprise_children_type',
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
        'enterprise' => 'required|exists:bm_enterprises,id',
    ];

    /**
     * @var string[]
     */
    public static $updateLogoMenuRules = [
        'enterprise' => 'required|exists:App\Models\Bm\Enterprise,id',
        'file' => [
            'required',
            'mimes:jpg,png,jpeg',
            'mimetypes:image/jpeg,image/png',
            'dimensions:max_width=222,max_height=55'
        ],
    ];

    /**
     * @var string[]
     */
    public static $updateLogoTicketRules = [
        'enterprise' => 'required|exists:App\Models\Bm\Enterprise,id',
        'file' => [
            'required',
            'mimes:jpg,png,jpeg',
            'mimetypes:image/jpeg,image/png',
            'dimensions:max_width=512,max_height=256'
        ],
    ];

    /**
     * @var string[]
     */
    public static $updateLogoA4Rules = [
        'enterprise' => 'required|exists:App\Models\Bm\Enterprise,id',
        'file' => [
            'required',
            'mimes:jpg,png,jpeg',
            'mimetypes:image/jpeg,image/png',
            'dimensions:max_width=512,max_height=256'
        ],
    ];

    /**
     * @var string[]
     */
    public static $messageRules = [
        'required' => 'The :attribute is required.',
        'max' => 'The :attribute is very long.',
        'unique' => 'The :attribute has already been taken.',
        'exists' => 'Could not find :attribute',
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * account
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id')->whereNull('deleted_at');
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
     * location
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id')
            ->with(['parent'])
            ->whereNull('deleted_at');
    }

    /**
     * currency
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->whereNull('deleted_at');
    }

    /**
     * license
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class, 'license_id')
            ->whereNull('deleted_at');
    }

    /**
     * taxAdministrationSystem
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function taxAdministrationSystem(): BelongsTo
    {
        return $this->belongsTo(TaxAdministrationSystem::class, 'tax_administration_system_id')->whereNull('deleted_at');
    }

    /**
     * branches
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class)->with(['warehouses', 'terminals', 'paymentMethods'])->whereNull('deleted_at');
    }

    /**
     * representatives
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return HasMany
     */
    public function representatives(): HasOne
    {
        return $this->hasOne(Representative::class)->whereNull('deleted_at');
    }

    /**
     * subscriptions
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(PaymentInfrastructureSubscription::class)
            ->with(['payments'])
            ->whereNull('deleted_at');
    }

    /**
     * @return Attribute
     */
    protected function logoMenu(): Attribute
    {
        $disk = $this->getDisk();

        return Attribute::make(
          get:fn($value) => $value ? $disk->url($value) : null
        );
    }

    /**
     * @return Attribute
     */
    protected function logoTicket(): Attribute
    {
        $disk = $this->getDisk();

        return Attribute::make(
            get:fn($value) => $value ? $disk->url($value) : null
        );
    }

    /**
     * @return Attribute
     */
    protected function logoA4(): Attribute
    {
        $disk = $this->getDisk();

        return Attribute::make(
            get:fn($value) => $value ? $disk->url($value) : null
        );
    }
}
