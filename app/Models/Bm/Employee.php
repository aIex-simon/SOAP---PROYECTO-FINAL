<?php

namespace App\Models\Bm;

use App\Models\Iam\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'bm_employees';

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * @var string[]
     */
    public static $listRules = [
        'size' => 'nullable|integer|between:5,100',
        'page' => 'nullable|integer|min:0',
        'query' => 'nullable|string|min:3|max:50',
    ];

    /**
     * @var string[]
     */
    public static $registerRules = [
        'email' => 'required|string|email|max:255|unique:iam_users,email',
        'password' => 'required|string|min:6|max:200',
        'phone' => 'required|phone:PE',
        'request_change_password' => 'required|boolean',
        'name' => 'required|string|max:250',
        'last_name' => 'required|string|max:250',
        'date_birthday' => 'nullable|string|max:10|date_format:Y-m-d', // validar mayor de 18 años
        'role' => 'required|integer|exists:iam_roles,id',
        'identity_document_type' => 'required|integer|max:10|exists:fin_identity_document_types,id',
        'identity_document_number' => 'required|integer',
        'image' => 'mimes:jpg,png,jpeg|mimetypes:image/jpeg,image/png|max:830',
    ];

    /**
     * @var string[]
     */
    public static $viewRules = [
        'employee' => 'required|exists:App\Models\Bm\Employee,user_id,deleted_at,NULL'
    ];

    /**
     * @var string[]
     */
    public static $updateRules = [
        'employee' => 'required|exists:iam_users,id',
        'password' => 'string|min:6|max:200',
        'name' => 'required|string|max:250',
        'last_name' => 'required|string|max:250',
        'date_birthday' => 'nullable|string|max:10|date_format:Y-m-d', // validar mayor de 18 años
        'role' => 'required|integer|exists:iam_roles,id',
        'identity_document_type' => 'required|integer|max:10|exists:fin_identity_document_types,id',
        'identity_document_number' => 'required|integer',
        'image' => 'mimes:jpg,png,jpeg|mimetypes:image/jpeg,image/png|max:830',
    ];

    /**
     * @var string[]
     */
    public static $inviteRules = [
        'email' => 'required|string|email|max:255',
    ];

    /**
     * @var string[]
     */
    public static $acceptInvitationRules = [
        'id' => 'required|integer|exists:iam_users,id'
    ];

    /**
     * @var string[]
     */
    public static $statusRules = [
        'employee' => 'required|integer|exists:iam_users,id',
        'active' => 'required|boolean'
    ];

    /**
     * @var string[]
     */
    public static $messageRules = [
        'required' => 'The :attribute is required.',
        'max' => 'The :attribute is very long.',
        'exists' => 'Could not find :attribute'
    ];

    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
