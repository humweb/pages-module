<?php

namespace Humweb\Pages\Repositories;

class Presenter
{
    protected $chunks;

    public function __construct($chunks)
    {
        $this->chunks = $chunks;
    }

    /**
     * Render the Bootstrap chunkss contents.
     *
     * @return string
     */
    public function render()
    {
        $str = '';

        foreach ($this->chunks as $rows) {
            if (!empty($rows['columns'])) {
                $str .= $this->gridRowOpen();

                foreach ($rows['columns'] as $column) {
                    $str .= $this->gridColumn($column['content'], $column['size']);
                }

                $str .= $this->gridRowClose();
            }
        }

        return $str;
    }

    public function gridRowOpen()
    {
        return '<div class="row">';
    }

    public function gridRowClose()
    {
        return '</div>';
    }

    public function gridColumn($content = '', $width = 12)
    {
        return '<div class="columns large-'.$width.'">'.$content.'</div>';
    }
}
