<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class PageCategory extends Model
{
    use SoftDeletes;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    /**
     * @var string
     */
    protected $table = 'bm_page_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'code_lang',
        'status',
    ];

    /**
     * @var array
     */
    public static array $registerRules = [
        'name' => 'required|string|max:250',
        'lang' => 'required|in:es,en',
    ];

    /**
     * @var array
     */
    public static array $getRules = [
        'page' => 'required|integer',
    ];

    /**
     * @var array
     */
    public static array $updateRules = [
        'page' => 'required|integer|exists:App\Models\Bm\PageCategory,id',
        'name' => 'required|string|max:250',
        'lang' => 'required|in:es,en',
    ];

    /**
     * @var array
     */
    public static array $deleteRules = [
        'page' => 'required|integer|exists:App\Models\Bm\PageCategory,id',
    ];
}
