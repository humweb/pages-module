<?php

namespace Humweb\Pages\Models;

use Humweb\Tags\Models\TaggableTrait;
//use Humweb\Pages\Models\PagesInterface as PagesInterface;


/*
    @todo Build low-level metadata component
 */
class PageType extends \Eloquent
{
    use TaggableTrait;

    //The names of the tables
    protected $table = 'pages';

    protected $tagger;

    protected $fillable = array(
        'title', 'uri', 'created_by', 'published_at', 'parent_id', 'slug', 'content',
        'published', 'js', 'css', 'is_index', 'meta_title','meta_description','meta_robots', 'order', );

    public $rules = [
        'title' => 'required|min:3|unique:pages,title',
        'slug' => 'required_with:title|min:3|alpha_dash|unique:pages,slug',
        'content' => 'required|min:10',
        'published' => 'in:0,1',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }
    public function categories()
    {
        return $this->belongsToMany('Humweb\Pages\Models\Category', 'category_rel', 'page_id', 'category_id');
    }

    // public function tags()
    // {
    //     return $this->belongsToMany('Humweb\Pages\Models\Tag', 'tag_rel', 'page_id', 'tag_id');
    // }

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
