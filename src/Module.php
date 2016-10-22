<?php

namespace Humweb\Pages;

use Humweb\Module\AbstractModule;

class Module extends AbstractModule
{
    public $name = 'Pages';
    public $version = '1.1';
    public $author = 'Ryun SHofner';
    public $website = 'humboldtweb.com';
    public $license = 'BSD-3-Clause';
    public $description = 'Pages Module';
    public $admin_section = 'Content';
    public $autoload = [
        'routes.php',
    ];

    public function boot()
    {
        $this->app->bind('Humweb\Pages\Repositories\DbPageRepositoryInterface',
                         'Humweb\Pages\Repositories\DbPageRepository');
    }

    public function install()
    {
        Schema::create('pages', function ($table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->string('uri');
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->boolean('published');
            $table->integer('created_by');
            $table->datetime('published_at');
            $table->timestamps();
            $table->text('css');
            $table->text('js');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->enum('meta_robots', array('noindex', 'nofollow', 'both', 'index', 'all'))->default('all');
            $table->boolean('is_index');
            $table->boolean('comment_status');
            $table->integer('order');

            $table->index('parent_id');
            $table->index('created_at');
            $table->index('uri');
        });

        $homePage = Page::create([
            'created_by' => 1,
            'slug' => 'home',
            'parent_id' => 0,
            'uri' => 'home',
            'title' => 'Home',
            'content' => 'Welcome to your new site!',
            'published' => 1,
            'is_index' => 1,
            'order' => 0,
        ]);

        return $homePage->slug == 'home';
    }

    public function upgrade()
    {
        return true;
    }

    public function uninstall()
    {
        Schema::dropIfExists('pages');

        return true;
    }

    public function admin_menu()
    {
        return [
            'Content' => [
                [
                    'label' => 'Pages',
                    'url' => url('/admin/pages'),
                    'icon' => '<i class="fa fa-book" ></i>',
                    'children' => [
                        ['label' => 'List', 'url' => url('/admin/pages')],
                        ['label' => 'Create', 'url' => url('/admin/pages/create')],
                    ],
                ],
            ],
        ];
    }

    public function admin_quick_menu()
    {
        return [
            'index' => [
                ['label' => 'Add Page', 'url' => '/admin/pages/create'],
            ],
        ];
    }
}
