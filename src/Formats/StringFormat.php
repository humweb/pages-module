<?php

namespace Humweb\Pages\Formats;

/**
 * StringContent.
 */
class StringFormat extends AbstractFormat
{
    /**
     * {@inheritDoc}
     */
    public function toHtml()
    {
        return $this->getValue();
    }

    public function render()
    {
        return $this->getValue();
    }
}
