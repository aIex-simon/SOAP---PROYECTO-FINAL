<?php

namespace App\Models\Bm;

use App\Models\Iam\UserProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrinterLink extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'bm_printer_links';

    protected $primaryKey = ['printer_id', 'terminal_id', 'employee_id'];
    public $incrementing = false;
    protected $keyType = 'array';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'printer_id',
        'terminal_id',
        'employee_id',
        'printer_connection_type_id',
        'printer_paper_sheet_type_id',
        'flag_print_option',
        'flag_auto_printing_tickets',
        'flag_drawer_open',
        'ip',
        'mac',
        'usb',
        'default',
        'status',
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * printer
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function printer(): BelongsTo
    {
        return $this->belongsTo(Printer::class, 'printer_id')->with('model');
    }

    /**
     * terminal
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'terminal_id')->select(['id', 'terminal_type_id', 'name']);
    }

    /**
     * userProfile
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'employee_id', 'user_id')->select(['user_id', 'name', 'lastname']);
    }

    /**
     * printerConnectionType
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function printerConnectionType(): BelongsTo
    {
        return $this->belongsTo(PrinterConnectionType::class, 'printer_connection_type_id')->select(['id', 'name']);
    }

    /**
     * printerPaperSheetType
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return BelongsTo
     */
    public function printerPaperSheetType(): BelongsTo
    {
        return $this->belongsTo(PrinterPaperSheetType::class, 'printer_paper_sheet_type_id')->select(['id', 'name']);
    }
}
