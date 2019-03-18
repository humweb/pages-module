<?php

namespace Humweb\Pages\Presenters;

/**
 * PagePresenter.
 */
class PagePresenter
{
    /**
     * Build Select box.
     *
     * @param array  $tree
     * @param int    $level
     * @param string $prefix
     *
     * @return string
     */
    public function selectBoxPages($tree = array(), $level = 0, $prefix = '&nbsp;')
    {
        $output = '';

        if (is_array($tree)) {
            foreach ($tree as $node) {
                $pre = ($level > 0) ? str_repeat($prefix, $level).' ' : '';

                $output .= '<option value="'.$node['uri'].'">'.$pre.$node['title'].'</option>'."\n";

                if (isset($node['children']) && ! empty($node['children'])) {
                    $output .= $this->selectBoxPages($node['children'], $level + 1, $prefix);
                }
            }

            return $output;
        }
    }


    public function nestedAdminTree($tree = null)
    {
        $output = '';

        if ( ! $tree->isEmpty()) {
            foreach ($tree as $node) {

                $output .= '<li class="dd-item" data-id="'.$node->id.'">'.'<div class="dd-handle">&nbsp;</div>'.'<div class="dd-content">'.$node->title;
                $output .= ($node->published) ? '' : ' <span class="badge badge-pill badge-secondary">draft</span>';
                $output .= '<div class="actions">'.'<div class="btn-group">'.'<a href="/admin/pages/info/'.$node->id.'" class="tip" title="Info"><i class="fa fa-info"></i></a>'.'<a href="'.route('get.admin.pages.create',
                        [$node->id]).'" class="tip" title="Add child page"><i class="fa fa-plus"></i></a>'.'<a href="'.route('get.admin.pages.edit',
                        [$node->id]).'" class="tip" title="Edit"><i class="fa fa-edit"></i></a>'.'<a href="'.route('get.admin.pages.delete',
                        [$node->id]).'" class="tip" title="Delete"><i class="fa fa-trash"></i></a>'.'</div>'.'</div></div>';
                if ( ! $node->items->isEmpty()) {
                    $output .= '<ol class="dd-list">'.$this->nestedAdminTree($node->items).'</ol>';
                }
                $output .= '</li>';
            }

            return $output;
        }
    }
}
