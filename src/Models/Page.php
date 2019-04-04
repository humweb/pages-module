<?php

namespace Humweb\Pages\Models;

use Humweb\Auth\Users\User;
use Humweb\Core\Data\Nestable\NestableTrait;
use Humweb\Core\Data\Traits\HasRelatedContent;
use Humweb\Core\Data\Traits\SluggableTrait;
use Humweb\Core\Data\Traits\SortablePosition;
use Humweb\Tags\Models\TaggableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Page model
 *
 * @package Humweb\Pages\Models
 */
class Page extends Model
{
    use TaggableTrait, SortablePosition, HasRelatedContent, NestableTrait, SluggableTrait;

    // Page status constants
    const STATUS_DISABLED  = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT     = 2;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'uri',
        'created_by',
        'published_at',
        'parent_id',
        'slug',
        'layout',
        'content',
        'published',
        'js',
        'css',
        'is_index',
        'meta_title',
        'meta_description',
        'meta_robots',
        'order',
    ];

    protected $attributes = [
        'created_by' => 0,
        'parent_id'  => 0,
        'published'  => 0,
    ];

    protected $dates = [
        'published_at'
    ];

    protected $versionsEnabled = true;


    /**
     * Page constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->slugOptions = [
            'maxlen'     => 200,
            'unique'     => true,
            'slug_field' => 'slug',
            'from_field' => 'title',
        ];

        $this->initSortable([
            'column' => 'order',
            'scope'  => 'parent_id',
        ]);
    }


    /**
     * Children relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id', 'id')->orderBy('order', 'ASC');
    }


    /**
     * Parent relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id', 'id');
    }


    /**
     * User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    /**
     * @param $query
     *
     * @return mixed
     */
    // TODO: refactor to status column
    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }


    public function scopeUri($query, $uri)
    {
        return $query->where('uri', $uri);
    }


    public function isRoot()
    {
        return $this->parent_id == 0;
    }


    public function isChild()
    {
        return $this->parent_id > 0;
    }

}
