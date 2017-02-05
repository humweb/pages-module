<?php

namespace Humweb\Tests\Pages;

use Humweb\Pages\Models\Page;
use Humweb\Pages\Repositories\DbPageRepository;

class SortablePagePositionTest extends TestCase
{
    protected $runMigrations = true;
    protected $page;


    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->page = new DbPageRepository();
        foreach (range(1, 5) as $num) {
            $page = $this->createPage('Page '.$num);

            if ($num % 2) {
                $page->children()->saveMany([
                    $this->createChildPage($page, 'Page '.$num.'.1'),
                    $this->createChildPage($page, 'Page '.$num.'.2'),
                    $this->createChildPage($page, 'Page '.$num.'.3'),
                ]);
            } else {
                $page->children()->saveMany([
                    $this->createChildPage($page, 'Page '.$num.'.1')
                ]);
            }
        }
        $this->createPage('No Children');
    }


    /**
     * @test
     */
    public function it_can_query_children()
    {
        $this->assertEquals(3, $this->page->find(1)->children()->count());
    }


    /**
     * @test
     */
    public function it_sorts_new_item()
    {
        // Prepare/Act
        $this->createChildPage(1, 'Page 1.4');
        $this->createChildPage(1, 'Page 1.5');

        // Assert
        $this->seeInDatabase('pages', ['title' => 'Page 1.4', 'position' => 4, 'parent_id' => 1]);
        $this->seeInDatabase('pages', ['title' => 'Page 1.5', 'position' => 5, 'parent_id' => 1]);
    }


    /**
     * @test
     */
    public function it_sorts_when_moving_groups()
    {
        // Prepare
        $item = $this->page->find(2);

        // Act
        $this->page->reorder($item, 1, 5);

        // Assert
        $this->seeInDatabase('pages', ['id' => 3, 'position' => 1, 'parent_id' => 1]);
        $this->seeInDatabase('pages', ['id' => 4, 'position' => 2, 'parent_id' => 1]);

        $this->seeInDatabase('pages', ['id' => 2, 'position' => 1, 'parent_id' => 5]);
        $this->seeInDatabase('pages', ['id' => 6, 'position' => 2, 'parent_id' => 5]);
        $this->seeInDatabase('pages', ['id' => 3, 'position' => 1, 'parent_id' => 1]);
    }


    /**
     * @test
     */
    public function it_sorts_when_moving_groups_an_no_position_set()
    {

        // Prepare
        $item = $this->page->find(2);

        // Act
        $this->page->reorder(2, null, 5);

        // Assert
        $this->seeInDatabase('pages', ['id' => 3, 'position' => 1, 'parent_id' => 1]);
        $this->seeInDatabase('pages', ['id' => 4, 'position' => 2, 'parent_id' => 1]);

        $this->seeInDatabase('pages', ['id' => 6, 'position' => 1, 'parent_id' => 5]);
        $this->seeInDatabase('pages', ['id' => 2, 'position' => 2, 'parent_id' => 5]);
    }


    /**
     * @test
     */
    public function it_sorts_when_moving_to_empty_parent()
    {
        // Prepare
        $item  = $this->page->getBySlug('no-children');
        $child = $this->page->find(2);

        // Act
        $this->page->reorder($child, null, $item->id);

        // Assert
        $this->seeInDatabase('pages', ['id' => 3, 'position' => 1, 'parent_id' => 1]);
        $this->seeInDatabase('pages', ['id' => 4, 'position' => 2, 'parent_id' => 1]);

        $this->seeInDatabase('pages', [
            'id'        => 2,
            'position'  => 1,
            'parent_id' => $item->id,
            'uri'       => $item->slug.'/'.$child->slug
        ]);
    }


    /**
     * @test
     */
    public function it_sorts_within_a_group()
    {
        // Prepare
        $page = $this->page->find(3);

        // Act
        $this->page->reorder($page, 1, null);

        // Assert
        $this->seeInDatabase('pages', ['id' => 3, 'position' => 1, 'parent_id' => 1]);
        $this->seeInDatabase('pages', ['id' => 2, 'position' => 2, 'parent_id' => 1]);
        $this->seeInDatabase('pages', ['id' => 4, 'position' => 3, 'parent_id' => 1]);
    }


    /**
     * @param string $title
     *
     * @return mixed
     */
    protected function createPage($title = '')
    {
        $slug = str_slug($title);

        return factory(Page::class)->create([
            'title' => $title,
            'uri'   => $slug,
            'slug'  => $slug
        ]);
    }


    /**
     * @param int    $parent
     * @param string $title
     *
     * @return mixed
     */
    protected function createChildPage($parent = 0, $title = '')
    {
        if (is_integer($parent)) {
            $parent = Page::find($parent);
        }
        $slug = str_slug($title);

        return factory(Page::class)->create([
            'title'     => $title,
            'slug'      => $slug,
            'uri'       => $parent->uri.'/'.$slug,
            'parent_id' => $parent->id
        ]);
    }

}
