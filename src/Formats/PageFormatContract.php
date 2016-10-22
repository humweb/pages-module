<?php

namespace Humweb\Pages\Formats;

/**
 * FormatContract.
 */
interface PageFormatContract
{
    /**
     * Creates a new content instance.
     *
     * @param string $value
     */
    public function __construct($value);

    /**
     * Returns the editor for type format.
     *
     * @return string
     */
    public function render();
}
