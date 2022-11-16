<?php

namespace App\Models\Iam;

use App\Models\Bm\TerminalEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    /**
     * @var string
     */
    protected $table = 'iam_user_profiles';

    protected $primaryKey = 'user_id';

    public const DIRECTORY_IMAGE_PROFILE = 'profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'identity_document_type_id',
        'identity_document_number',
        'name',
        'lastname',
        'date_birthday',
        'image',
    ];

    /**
     * @return HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(TerminalEmployee::class, 'employee_id', 'user_id');
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
