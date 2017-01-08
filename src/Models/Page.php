<?php

namespace Humweb\Pages\Models;

use Humweb\Core\Data\Traits\HasRelatedContent;
use Humweb\Core\Data\Traits\SluggableTrait;
use Humweb\Tags\Models\TaggableTrait;
use Humweb\Core\Data\Nestable\NestableTrait;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use TaggableTrait, SluggableTrait, HasRelatedContent, NestableTrait;

    //The names of the tables
    protected $table = 'pages';

    protected $tagger;

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
        'parent_id' => 0,
        'published' => false,
    ];

    public $rules = [
        'title'     => 'required|min:3|unique:pages,title',
        'slug'      => 'required_with:title|min:3|alpha_dash|unique:pages,slug',
        'content'   => 'required|min:10',
        'published' => 'in:0,1',
    ];


    protected $versionsEnabled = true;

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED  = 1;
    const STATUS_DRAFT = 2;


    public function __construct(array $attributes = array())
    {

        parent::__construct($attributes);

        $this->slugOptions = [
            'maxlen'     => 200,
            'unique'     => true,
            'slug_field' => 'slug',
            'from_field' => 'title',
        ];

    }

    public function user()
    {
        return $this->belongsTo('User', 'created_by');
    }

    public function scopeTerms($query, $terms, $column = 'content')
    {
        if (is_string($terms)) {
            if (strstr(',', $terms)) {
                $terms = explode(',', str_replace(' ', '', $terms));
            } elseif (strstr(' ', $terms)) {
                $terms = explode(' ', $terms);
            } else {
                return $query->where($column, 'like', '%'.$term.'%');
            }
        }

        foreach ($terms as $term) {
            $query->where($column, 'like', '%'.$term.'%');
        }

        return $query;
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    public function scopeUri($query, $uri)
    {
        return $query->where('uri', $uri);
    }
}
