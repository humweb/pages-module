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
     * Returns a ordered tree array of pages.
     *
     * @return array
     */
    public function tree();

    /*** Reorder *****************************************************/

    /**
     * Reorder pages.
     *
     * @param $pages
     *
     * @return mixed
     */
    public function reorder($pages);


    /**
     * Build a lookup.
     *
     * @param int $id The id of the page to build the lookup for.
     *
     * @return array
     */
    public function reindex($id);
}
