<?php

namespace Humweb\Pages\Controllers;

use Carbon\Carbon;
use Humweb\Core\Http\Controllers\AdminController;
use Humweb\Pages\Layouts;
use Humweb\Pages\Models\Page;
use Humweb\Pages\Presenters\PagePresenter;
use Humweb\Pages\Repositories\DbPageRepositoryInterface;
use Humweb\Pages\Requests\PageSaveRequest;
use Humweb\Tags\Models\Tag;
use Illuminate\Http\Request;

class AdminPagesController extends AdminController
{
    protected $page;
    protected $data;


    public function __construct(DbPageRepositoryInterface $page, Tag $tag)
    {
        parent::__construct();

        $this->crumb('Pages', '/admin/pages');

        $this->page = $page;
        $this->tag  = $tag;
    }


    public function postSort(Request $request)
    {
        $this->page->reorder($request->get('item'), $request->get('position'), $request->get('parent'));

        return response()->json(['status' => 'ok']);
    }


    /**
     * Get Create Page form.
     *
     * @param int $parent_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate(Layouts $layouts, $parent_id = 0)
    {
        $parent_page = null;

        // Fetch parent page
        if ($parent_id > 0) {
            $parent_page = $this->page->find($parent_id);
        }

        $this->data = [
            'page'           => new Page(),
            'layouts'        => $layouts->lists(),
            'parent_id'      => $parent_id,
            'parent_page'    => $parent_page,
            'title'          => 'Create Post',
            'available_tags' => $this->tag->select('slug', 'name')->orderBy('name', 'asc')->pluck('name', 'slug'),
            'current_tags'   => [],
        ];
        $this->viewShare('title', 'Create');
        $this->crumb('Create');

        return $this->setContent('pages::admin.form', $this->data);
    }


    /**
     * Create Page.
     */
    public function postCreate(PageSaveRequest $request, $parent_id = 0)
    {

        $slug = str_slug($request->get('slug', $request->get('title')));

        $request->get('parent_id', 0);

        $data = [
            'created_by'       => $request->user()->id,
            'slug'             => $slug,
            'parent_id'        => $request->get('parent_id', 0),
            'title'            => $request->get('title'),
            'layout'           => $request->get('layout'),
            'content'          => $request->get('content'),
            'published'        => $request->get('published'),
            'css'              => $request->get('css'),
            'js'               => $request->get('js'),
            'meta_title'       => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
            'meta_robots'      => $request->get('meta_robots'),
            'is_index'         => $request->get('is_index', 0)
        ];

        //TODO: Check for publish permissions

        // Set published on date
        if ($data['published'] == 1) {
            $data['published_at'] = Carbon::now();
        }

        $page = Page::create($data);
        redirect()->route('get.admin.pages.index')->with('success', 'Page added.');
    }


    public function getIndex(Request $request)
    {
        $presenter = new PagePresenter();
        $this->viewShare('title', 'Page Manager');
        $pageTree              = $this->data['pages'] = $this->page->tree();
        $this->data['content'] = $presenter->nestedAdminTree($pageTree);

        return $this->setContent('pages::admin.index', $this->data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \Humweb\Pages\Layouts $layouts
     * @param int                   $id
     *
     * @return \Humweb\Pages\Controllers\Response
     */
    public function getEdit(Layouts $layouts, $id)
    {
        $page = $this->page->find($id);

        $this->data = [
            'layouts'        => $layouts->lists(),
            'page'           => $page,
            'available_tags' => $this->tag->select('slug', 'name')->orderBy('name', 'asc')->pluck('name', 'slug'),
            'current_tags'   => ($page->tagged->count() > 0) ? $page->tagged()->pluck('name', 'slug') : [],
        ];
        $this->crumb('Edit');
        $this->viewShare('title', 'Edit Post: '.$page->title);

        return $this->setContent('pages::admin.edit', $this->data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Humweb\Pages\Requests\PageSaveRequest $request
     * @param int                                    $id
     *
     * @return \Humweb\Pages\Controllers\Response
     */
    public function postEdit(PageSaveRequest $request, $id)
    {
        $page   = $this->page->find($id);
        $pageId = $page->id;

        $is_index = $request->get('is_index', 0) == 1;

        $data = [
            'title'            => $request->get('title'),
            'content'          => $request->get('content'),
            'layout'           => $request->get('layout'),
            'slug'             => str_slug($request->get('slug')),
            'uri'              => str_slug($request->get('slug')),
            'created_by'       => $request->user()->id,
            'css'              => $request->get('css'),
            'js'               => $request->get('js'),
            'published'        => $request->get('published'),
            'meta_title'       => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
            'meta_robots'      => $request->get('meta_robots'),
            'is_index'         => $request->get('is_index', $is_index),
        ];

        // remove index page if needed
        if ($is_index) {
            $this->page->removeIndexPageStatus();
        } // Toggle published / published
        elseif ($data['published'] == 0) {
            $data['published_at'] = null;
        } elseif ($data['published'] != $page->published && $data['published'] == 1) {
            $data['published_at'] = Carbon::now();
        }

        //Save page
        $this->page->update($pageId, $data);

        //Save tags for page
        if ($request->has('tags')) {
            $page->saveTags($request->get('tags'));
        }

        $response = $request->get('save_exit') ? redirect()->route('get.admin.pages.index') : redirect()->route('get.admin.pages.edit', [$id]);

        return $response->with('success', 'The item has been updated.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getDelete($id)
    {
        if ($this->page->delete($id)) {
            return redirect()->route('get.admin.pages.index')->with('success', 'The item has been deleted.');
        }

        return redirect()->route('get.admin.pages.index')->withErrors('The item you tried to delete does not exist.');
    }
}
