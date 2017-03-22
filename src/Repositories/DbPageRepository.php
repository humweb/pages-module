<?php

namespace Humweb\Pages\Repositories;

use Humweb\Core\Data\Repositories\EloquentRepository;
use Humweb\Pages\Models\Page;

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
     * Get page by URI string.
     *
     * @param $slug
     *
     * @return mixed
     */
    public function getBySlug($slug)
    {
        return $this->createModel()->where('slug', $slug)->first();
    }


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


    // --------------------------------------------------------------------------


    /**
     * Update URI paths for parent and child pages
     *
     * @param integer|Page $item
     * @param integer      $toParentId
     * @param integer      $fromParentId
     */
    public function updateHierarchy($item, $toParentId, $fromParentId)
    {
        if ( ! ($item instanceof Page)) {
            $item = $this->find($item);
        }

        if ($toParentId == 0) {
            $item->uri = $item->slug;
            $item->save();
            $this->updateChildPaths($item);
        } // Change parent
        elseif ($toParentId != $fromParentId) {

            $this->updatePagePath($item);

            $this->updateChildPaths($item);
        }
    }


    /**
     * Update URI path
     *
     * @param $item
     */
    protected function updatePagePath($item)
    {
        $page       = $item;
        $current_id = $item->id;
        $segments   = [];

        // Build uri for moved page
        while ($page->parent_id > 0) {
            $page       = $this->createModel()->select('slug', 'parent_id')->find($current_id);
            $current_id = $page->parent_id;

            array_unshift($segments, $page->slug);
        }
        $item->uri = implode('/', $segments);
        $item->save();
    }


    public function updateChildPaths($parent)
    {
        $this->createModel()->select('id', 'uri', 'slug')->where('parent_id', $parent->id)->get()->each(function ($child) use ($parent) {
            $child->uri = $parent->uri.'/'.$child->slug;
            $child->save();
            $this->updateChildPaths($child);
        });
    }


    /**
     * Reorder pages.
     *
     * @param $page
     * @param $position
     * @param $toParentId
     *
     *
     * @return mixed
     */
    public function reorder($page, $position = null, $toParentId = null)
    {
        if ( ! ($page instanceof Page)) {
            $page = $this->find($page);
        }

        $fromParentId = $page->parent_id;
        $page->order  = $position;

        if ( ! is_null($toParentId)) {
            $page->parent_id = $toParentId;
            $page->uri       = $page->slug;
        }
        $page->save();

        $this->updateHierarchy($page, $toParentId, $fromParentId);
    }

    /*** Reorder *****************************************************/

    // --------------------------------------------------------------------------

    /**
     * @param bool   $published
     * @param array  $tree
     * @param int    $level
     * @param string $prefix
     *
     * @return string
     */
    public function build_select($published = false, $tree = [], $level = 0, $prefix = '-')
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
                    $output .= $this->build_select($published, $leaf['children'], $level + 1, $prefix);
                }
            }

            return $output;
        }
    }


    /**
     * Remove's index page status from previous page
     *
     * @return integer
     */
    public function removeIndexPageStatus()
    {
        return $this->createModel()->where('is_index', 1)->update(['is_index' => 0]);
    }


    /**
     * Returns a ordered tree array of pages.
     *
     * @param bool $published
     *
     * @return array
     */
    public function tree($published = false)
    {
        $q = $this->createModel()->select('id','parent_id','title','uri')->orderBy('parent_id')->orderBy('order');

        return $published ? $q->published()->get()->nest() : $q->get()->nest();
    }

}
