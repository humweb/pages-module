<?php

namespace Humweb\Pages\Repositories;

use Humweb\Core\Contracts\Data\CrudRepositoryInterface;

/**
 * DbPageRepositoryInterface.
 */
interface DbPageRepositoryInterface extends CrudRepositoryInterface
{
    /**
     * Get index page.
     *
     * @return mixed
     */
    public function getIndex();


    /**
     * Get page by uri index.
     *
     * @param $uri
     *
     * @return mixed
     */
    public function getByUri($uri);


    /**
     * Get page by URI string.
     *
     * @param $slug
     *
     * @return mixed
     */
    public function getBySlug($slug);


    /**
     * Returns a ordered tree array of pages.
     *
     * @param bool $published
     *
     * @return array
     */
    public function tree($published = false);

    /**
     * @param bool   $published
     * @param array  $tree
     * @param int    $level
     * @param string $prefix
     *
     * @return string
     */
    public function build_select($published = false, $tree = [], $level = 0, $prefix = '-');

    /*** Reorder *****************************************************/

    /**
     * @param integer|Page $item
     * @param integer      $toParentId
     * @param integer      $fromParentId
     */
    public function updateHierarchy($item, $toParentId, $fromParentId);


    /**
     * Remove's index page status from previous page
     *
     * @return void
     */
    public function removeIndexPageStatus();
}
