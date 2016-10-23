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
        if (is_array($tree)) {
            foreach ($tree as $node) {
                $output .= '<li class="dd-item dd3-item" data-id="'.$node['id'].'">'.'<div class="dd-handle dd3-handle">Handle</div>'.'<div class="dd3-content">'.$node['title'];
                $output .= ($node['published'] == 0) ? '<span class="label round">draft</span>' : '';
                $output .= '<div class="actions">'.'<div class="btn-group">'.'<a href="/admin/pages/info/'.$node['id'].'" class="tip" title="Info"><i class="fa fa-info"></i></a>'.'<a href="'.route('get.admin.pages.create',
                        [$node['id']]).'" class="tip" title="Add child page"><i class="fa fa-plus"></i></a>'.'<a href="'.route('get.admin.pages.edit',
                        [$node['id']]).'" class="tip" title="Edit"><i class="fa fa-edit"></i></a>'.'<a href="'.route('get.admin.pages.delete',
                        [$node['id']]).'" class="tip" title="Delete"><i class="fa fa-trash"></i></a>'.'</div>'.'</div></div>';
                if (isset($node['children']) && ! empty($node['children'])) {
                    $output .= '<ol class="dd-list dd3-list">'.$this->nestedAdminTree($node['children']).'</ol>';
                }
                $output .= '</li>';
            }

            return $output;
        }
    }
}
