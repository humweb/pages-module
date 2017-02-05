<?php

namespace Humweb\Pages;

use Humweb\Modules\ModuleBaseProvider;

class ServiceProvider extends ModuleBaseProvider
{
    protected $permissions = [

        // Users
        'pages.create' => [
            'name'        => 'Create Pages',
            'description' => 'Create pages.',
        ],
        'pages.edit'   => [
            'name'        => 'Edit Pages',
            'description' => 'Edit pages.',
        ],
        'pages.list'   => [
            'name'        => 'List Pages',
            'description' => 'List pages.',
        ],
        'pages.delete' => [
            'name'        => 'Delete Pages',
            'description' => 'Delete pages.',
        ],
    ];

    protected $moduleMeta = [
        'name'    => 'Pages CMS',
        'slug'    => 'pages',
        'version' => '',
        'author'  => '',
        'email'   => '',
        'website' => '',
    ];


    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->app['modules']->put('pages', $this);
        $this->loadLang();
        $this->loadViews();
        $this->publishViews();
    }


    public function register()
    {
        $this->app->bind('Humweb\Pages\Repositories\DbPageRepositoryInterface', 'Humweb\Pages\Repositories\DbPageRepository');
    }


    public function getAdminMenu()
    {
        return [
            'Content' => [
                [
                    'label'    => 'Pages',
                    'url'      => url('/admin/pages'),
                    'icon'     => '<i class="fa fa-book" ></i>',
                    'children' => [
                        ['label' => 'Manage', 'url' => route('get.admin.pages.index')],
                        ['label' => 'Create', 'url' => route('get.admin.pages.create')],
                    ],
                ],
            ],
        ];
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
