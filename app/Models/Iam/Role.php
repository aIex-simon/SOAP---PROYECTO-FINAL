<?php

namespace App\Models\Iam;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as RoleBase;

class Role extends RoleBase
{
    use SoftDeletes;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    public const ID_MANAGEMENT = 1; // gestión
    public const ID_SALES = 2; // ventas
    public const ID_SHOPPING = 3; // compras
    public const ID_LOGISTIC = 4; // logística
    public const ID_ACCOUNTING = 5; // contabilidad

    protected $table = 'iam_roles';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
        'status'
    ];

    /**
     * @var string[]
     */
    public static $listRules = [
        'size' => 'nullable|integer|between:5,100',
        'page' => 'nullable|integer|min:0',
    ];

    /**
     * @var string[]
     */
    public static $showRules = [
        'role' => 'required|exists:App\Models\Iam\Role,id',
    ];

    /**
     * @var string[]
     */
    public static $storeRules = [
        'name' => 'required|max:200|unique:\App\Models\Iam\Role,name',
    ];

    /**
     * @var string[]
     */
    public static $updateRules = [
        'name' => 'required|max:200',
    ];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
