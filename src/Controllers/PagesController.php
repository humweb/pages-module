<?php

namespace Humweb\Pages\Controllers;

use Humweb\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Humweb\Menus\Models\MenuItem;
use Humweb\Pages\Models\Page;
use Humweb\Pages\Repositories\DbPageRepositoryInterface;


/**
 * @todo  Settings
 *        - Template grid type
 */
class PagesController extends Controller
{
    protected $page;
    protected $chunks;
    protected $crumbs;

    public function __construct(DbPageRepositoryInterface $page)
    {
        parent::__construct();
        $menu = MenuItem::build_navigation(1);
        $this->viewShare('menu', $menu);

        $this->page = $page;
    }

    // --------------------------------------------------------------------------

    /**
     * Catch the URI string and send to the index method.
     *
     * @param array $uri
     *
     * @return mixed|void
     */
    public function missingMethod($uri = [])
    {
        $this->getIndex($uri);
    }

    /**
     * Serves up dynamic pages by URI.
     *
     * @param string $uri
     *
     * @return \Illuminate\View\View
     */
    public function getIndex(Request $request, $uri = null)
    {

        $page = (is_null($uri))
            ? $this->page->getIndex()
            : $this->page->getByUri($uri);

        if ($page) {
            // ---------------------------------
            // Metadata
            // ---------------------------------

            //$this->setMeta('title', $page->meta_title ?: $page->title);
            //$this->setMeta('description', $page->meta_description ?: substr($page->content, 0, 50));


            // First we need to figure out our metadata. If we have meta for our page,
            // that overrides the meta from the page layout.
            $meta_keywords = [];
            foreach ($page->tagged->toArray() as $tag) {
                $meta_keywords[] = $tag['name'];
            }
            if (!empty($meta_keywords)) {
                //$this->setMeta('keywords', implode(',', $meta_keywords));
            }

            if ($page->meta_robots == 'index') {
                $meta_robots = 'index,follow';
            } elseif ($page->meta_robots == 'both') {
                $meta_robots = 'noindex,nofollow';
            } else {
                $meta_robots = $page->meta_robots;
            }

           // $this->setMeta('robots', $meta_robots);

            //$this->viewShare('metadata', implode("\n",$this->_metadata));

            if ($request->get('edit') == '1') {
                $page->content = '<div contenteditable="true">'.$page->content.'</div>';
                //$this->_metadata[] = '<script src="/assets/js/ckeditor/ckeditor.js"></script>';
            }

            if (!empty($page->layout)) {
                $this->setLayout($page->layout);
            }

            //$page->content = \StringView::create($page->content, (array)$page, $page->uri, $page->updated_at->timestamp);
            //$page->content = ShortParser::parse($page->content);
            //$this->viewShare('metadata', implode("\n",$this->_metadata));

            return $this->setContent('pages::layouts.default', $page);
        } else {
            App::abort(404);
        }
    }
}
