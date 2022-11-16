<?php

namespace App\Models\Iam;

use App\Models\Bm\Account;
use App\Models\Bm\Employee;
use App\Models\Bm\Terminal;
use App\Notifications\InviteEmail;
use App\Notifications\VerifyEmail;
use App\Traits\Auth\MustVerifyPhone;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    MustVerifyEmailContract
{
    use Authenticatable, Authorizable, HasFactory;
    use HasApiTokens, CanResetPassword;
    use Notifiable;
    use MustVerifyEmail;
    use MustVerifyPhone;
    use HasRoles;
    use SoftDeletes;

    protected $table = 'iam_users';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'phone_verified_at',
        'password',
        'remember_token',
        'flag_initial_config',
        'account_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function sendEmailInviteNotification()
    {
        $this->notify(new InviteEmail);
    }

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }

    /**
     * @return HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return ($this->employee()->first() ? $this->employee()->first()->user_id : null);
    }

    /**
     * @return mixed
     */
    public function getAccountId()
    {
        return ($this->account_id ? $this->account_id : null);
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setAccount(int $id)
    {
        return $this->forceFill([
            'account_id' => $id,
        ])->save();
    }

    /**
     * @param int $roleId
     */
    public function setRole(int $roleId)
    {
        $this->roles()->sync([$roleId]);
    }

    /**
     * @return mixed|null
     */
    public function getEnterpriseId()
    {
        if ($terminalAssigned = $this->getTerminalIdAssigned()) {
            $terminal = Terminal::query()->find($terminalAssigned);
            return $terminal?->enterprise_id;
        }

        return null;
    }
}
