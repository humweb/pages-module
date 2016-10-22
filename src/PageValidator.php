<?php

namespace Humweb\Pages;

use Humweb\Validation\Validation;

class PageValidator extends Validation
{
    protected $rules = [
        'default' => [
            'title' => 'required|min:3|unique:pages,title',
            'slug' => 'required_with:title|min:3|alpha_dash|unique:pages,slug',
            'content' => '',
            'published' => 'in:0,1',
        ],
        'update' => [
            'title' => 'required|min:3|unique:pages,title,{id}',
            'slug' => 'required_with:title|min:3|alpha_dash|unique:pages,slug,{id}',
            'content' => '',
            'published' => 'in:0,1',
        ],
    ];
}
