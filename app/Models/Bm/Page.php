<?php

namespace App\Models\Bm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    /**
     * @var string
     */
    protected $table = 'bm_pages';

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'title',
        'body',
        'slug',
        'code_lang',
        'status',
    ];

    /**
     * @var array
     */
    public static $registerRules = [
        'title' => 'required|string|between:5,254',
        'body' => 'required|string',
        'category' => 'required|integer|exists:App\Models\Bm\PageCategory,id',
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
        'page' => 'required|integer|exists:App\Models\Bm\Page,id',
        'title' => 'required|string|max:254',
        'category' => 'required|integer|exists:App\Models\Bm\PageCategory,id',
        'body' => 'required|string',
        'lang' => 'required|in:es,en',
    ];

    /**
     * @var array
     */
    public static array $deleteRules = [
        'page' => 'required|integer|exists:App\Models\Bm\Page,id',
    ];

    /**
     * @var array
     */
    public static array $showRules = [
        'slug_page' => 'required|string',
    ];
}
