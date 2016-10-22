<?php

namespace Humweb\Pages\Formats;

use Illuminate\Support\Manager;

/**
 * FormatManager.
 */
class FormatManager extends Manager
{
    protected function createMarkdownDriver()
    {
        return new MarkdownFormat();
    }

    protected function createStringDriver()
    {
        return new MarkdownFormat();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'string';
    }
}
