<?php

Route::group(['middleware' => 'auth'], function () {
    //GET
    Route::get('admin/pages', [
        'middleware' => 'allow.only:pages.list',
        'as'         => 'get.admin.pages.index',
        'uses'       => 'AdminPagesController@getIndex',
    ]);

    Route::get('admin/pages/create/{id?}', [
        'middleware' => 'allow.only:pages.create',
        'as'         => 'get.admin.pages.create',
        'uses'       => 'AdminPagesController@getCreate',
    ]);

    Route::get('admin/pages/edit/{id}', [
        'middleware' => 'allow.only:pages.edit',
        'message'    => 'You don\'t have permission to edit pages.',
        'as'         => 'get.admin.pages.edit',
        'uses'       => 'AdminPagesController@getEdit',
    ]);

    Route::get('admin/pages/delete/{id}', [
        'middleware' => 'allow.only:pages.delete',
        'as'         => 'get.admin.pages.delete',
        'uses'       => 'AdminPagesController@getDelete',
    ]);

    //POST
    Route::post('admin/pages/create/{id?}', [
        'middleware' => 'allow.only:pages.create',
        'as'         => 'post.admin.pages.create',
        'uses'       => 'AdminPagesController@postCreate',
    ]);

    Route::post('admin/pages/edit/{id}', [
        'middleware' => 'allow.only:pages.edit',
        'as'         => 'post.admin.pages.edit',
        'uses'       => 'AdminPagesController@postEdit',
    ]);

    Route::post('admin/pages/sort', [
        'middleware' => 'allow.only:pages.edit',
        'as'         => 'post.admin.pages.sort',
        'uses'       => 'AdminPagesController@postSort'
    ]);
});

Route::any('{path?}', [
    'as'   => 'get.pages.index',
    'uses' => 'PagesController@getIndex',
]);
