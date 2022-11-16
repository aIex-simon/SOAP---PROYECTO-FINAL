<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;

class TerminalEmployee extends Model
{
    protected $table = 'bm_terminal_employees';

    public $timestamps= false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'terminal_id',
        'employee_id',
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
