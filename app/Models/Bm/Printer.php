<?php

namespace App\Models\Bm;

use App\Models\Iam\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Printer extends Model
{
    protected $table = 'bm_printers';

    protected $primaryKey = 'peripheral_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'peripheral_id',
        'printer_model_id',
        'created_at',
        'updated_at'
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
    public static $storeRules = [
        'name' => 'required|string|max:100',
        'printer_model_id' => 'required|exists:App\Models\Bm\PrinterModel,id,deleted_at,NULL',
        'printer_connection_type_id' => 'required|exists:App\Models\Bm\PrinterConnectionType,id,deleted_at,NULL',
        'printer_paper_sheet_type_id' => 'required|exists:App\Models\Bm\PrinterPaperSheetType,id,deleted_at,NULL',
        'flag_print_option' => 'required|boolean',
        'flag_auto_printing_tickets' => 'required|boolean',
        'flag_drawer_open' => 'required|boolean',
        'default' => 'required|boolean',
        'ip' => 'required_if:printer_connection_type_id,'. PrinterConnectionType::ID_ETHERNET . '|nullable|string|max:15',
        'mac' => 'required_if:printer_connection_type_id,'. PrinterConnectionType::ID_BLUETOOTH . '|nullable|string|max:17',
        'usb' => 'required_if:printer_connection_type_id,'. PrinterConnectionType::ID_USB . '|nullable|string|max:6',
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
     * peripheral
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function peripheral(): BelongsTo
    {
        return $this->belongsTo(Peripheral::class, 'peripheral_id')
            ->select(['id', 'peripheral_category_id', 'enterprise_id', 'name'])
            ->whereNull('deleted_at');
    }

    /**
     * printerLinks
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return HasMany
     */
    public function printerLinks(): HasMany
    {
        /** @var User $user */
        $user = auth()->user();
        return $this->hasMany(PrinterLink::class, 'printer_id', 'peripheral_id')->where('employee_id', '=', $user->id);
    }

    /**
     * model
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(PrinterModel::class, 'printer_model_id')
            ->with(['brand', 'sdkType', 'connectionTypes'])
            ->whereNull('deleted_at');
    }
}
