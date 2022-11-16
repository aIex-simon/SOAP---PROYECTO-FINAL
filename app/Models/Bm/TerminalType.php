<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;

class TerminalType extends Model
{
    protected $table = 'bm_terminal_types';

    public const ID_STORE = 1;
    public const ID_BOX = 2;
    public const ID_WEB = 3;
}
