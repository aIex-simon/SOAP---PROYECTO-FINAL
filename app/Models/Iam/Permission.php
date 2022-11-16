<?php

namespace App\Models\Iam;

use Spatie\Permission\Models\Permission as PermissionBase;

class Permission extends PermissionBase
{
    public const PERMISSION_STATUS_ACTIVO = 0;
    public const PERMISSION_STATUS_INACTIVO = 1;
    public const PERMISSION_STATUS_ELIMINADO = 2;

    protected $table = 'iam_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
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
        'permission' => 'required|exists:App\Models\Iam\Permission,id',
    ];

    /**
     * @var string[]
     */
    public static $storeRules = [
        'name' => 'required|max:200|unique:\App\Models\Iam\Permission,name',
    ];

    /**
     * @var string[]
     */
    public static $updateRules = [
        'name' => 'required|max:200',
    ];
}
