<?php

namespace Humweb\Pages\Formats;

/**
 * FormatContract.
 */
interface EditorPresenterContract
{
    /**
     * Creates a new content instance.
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     */
    public function __construct($name, $value, $options = []);

    /**
     * Returns the content's value.
     *
     * @return string
     */
    public function render();
}
