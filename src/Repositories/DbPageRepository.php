<?php

namespace Humweb\Pages\Repositories;

use Humweb\Core\Data\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class DbPageRepository.
 */
class DbPageRepository extends EloquentRepository implements DbPageRepositoryInterface
{
    protected $model = 'Humweb\\Pages\\Models\\Page';


    /**
     * Get index page.
     *
     * @return mixed
     */
    public function getIndex()
    {
        return $this->createModel()->where('is_index', '=', '1')->first();
    }


    /**
     * Get page by URI string.
     *
     * @param $uri
     *
     * @return mixed
     */
    public function getByUri($uri)
    {
        return $this->createModel()->with(['tagged'])->published()->uri($uri)->first();
    }


    /**
     * Returns a ordered tree array of pages.
     *
     * @return array
     */
    public function tree()
    {
        return $this->createModel()->orderBy('parent_id')->orderBy('order')->get()->nest();
//        $pages    = [];
//        $pageList = $this->createModel()->select('id', 'parent_id', 'slug', 'uri', 'title', 'published')->orderBy('parent_id')->orderBy('order')->get()->toArray();
//
//        // First, re-index the array.
//        foreach ($pageList as $row) {
//            $pages[$row['id']] = $row;
//        }
////dd($pages);
//        $pageList = [];
//
//        // Build a multidimensional array of parent > children.
//        foreach ($pages as $id => $row) {
//
//            // This is a root page.
//            if ($row['parent_id'] == 0) {
//                $pageList[$id] = $pages[$id];
//            }
//
//            // Add this page to the children array of the parent page.
//            if (isset($pages[$row['parent_id']])) {
//                $pageList[$row['parent_id']]['children'][$id] = $pages[$id];
//            }
//
//
//        }
////dd($pageList);
//        return $pageList;
    }

    // --------------------------------------------------------------------------

    /**
     * Delete's page and shifts children up one level.
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        if ($page = $this->createModel()->find($id)) {
            // Shift children parent_id's up one
            $this->createModel()->where('parent_id', '=', $id)->update(array('parent_id' => $page->parent_id));
            $page->delete();

            return true;
        }

        return false;
    }

    /*** Reorder *****************************************************/

    /**
     * Reorder pages.
     *
     * @param array $pages
     */
    public function reorder($pages = [])
    {
        if (is_array($pages)) {
            //reset all parent > child relations

            $this->createModel()->where('parent_id', '!=',  0)->update(['parent_id' => 0]);

            foreach ($pages as $order => $node) {
                $root_ids[] = $node['id'];

                //set the order of the root pages
                $this->createModel()->where('id', $node['id'])->update(['order' => $order + 1]);
                $this->reorderChildPages($node);
            }
        }
        $this->updatePageUriIndex($root_ids);
    }


    /**
     * Set the parent > child relations and child order.
     *
     * @param array $page
     */
    public function reorderChildPages($page)
    {
        if (isset($page['children'])) {
            foreach ($page['children'] as $i => $child) {
                $this->createModel()->where('id', '=', $child['id'])->update(['parent_id' => $page['id'], 'order' => $i + 1]);

                //repeat as long as there are children
                if (isset($child['children'])) {
                    $this->reorderChildPages($child);
                }
            }
        }
    }
    // --------------------------------------------------------------------------

    /**
     * Update lookup.
     *
     * Updates lookup for entire page tree used to update
     * page uri after ajax sort.
     *
     * @param array $root_pages An array of top level pages
     */
    public function updatePageUriIndex($root_pages)
    {
        // Reindex root items
        $this->createModel()->where('parent_id', 0)->update(array('uri' => DB::raw('slug')));

        foreach ($root_pages as $page) {
            // Reindex child items
            $descendants = $this->getChildrenIds($page);
            foreach ($descendants as $descendant) {
                $this->reindex($descendant);
            }
        }
    }


    /**
     * Get the child IDs.
     *
     * @param int   $id       The ID of the page?
     * @param array $id_array ?
     *
     * @return array
     */
    public function getChildrenIds($id, $id_array = [])
    {

        $id_array[] = $id;

        if ($children = $this->createModel()->where('parent_id', $id)->pluck('id')) {
            // Recursive loop child -> children
            foreach ($children as $child) {
                $id_array = $this->getChildrenIds($child);
            }
        }

        return $id_array;
    }

    // --------------------------------------------------------------------------

    /**
     * Build a lookup.
     *
     * @param int $id The id of the page to build the lookup for.
     *
     * @return array
     */
    public function reindex($id)
    {
        $current_id = $id;

        $segments = [];
        do {
            $page       = $this->createModel()->select('slug', 'parent_id')->find($current_id);
            $current_id = $page->parent_id;
            array_unshift($segments, $page->slug);
        } while ($page->parent_id > 0);

        return $this->createModel()->where('id', $id)->update(['uri' => implode('/', $segments)]);
    }


    public function build_select($tree = [], $level = 0, $prefix = '-')
    {
        $output = '';
        if ( ! $tree) {
            $tree = $this->tree();
        }

        if (is_array($tree)) {
            foreach ($tree as $leaf) {
                $pre = ($level > 0) ? str_repeat($prefix, $level).' ' : '';

                $output .= '<option value="'.$leaf['uri'].'">'.$pre.$leaf['title'].'</option>'."\n";

                if (isset($leaf['children']) && ! empty($leaf['children'])) {
                    $output .= $this->build_select($leaf['children'], $level + 1, $prefix);
                }
            }

            return $output;
        }
    }
}
