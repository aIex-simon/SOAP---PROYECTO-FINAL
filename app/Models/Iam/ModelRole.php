<?php

namespace App\Models\Iam;

use Illuminate\Database\Eloquent\Model;

class ModelRole extends Model
{
    protected $table = 'iam_model_roles';

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
