<?php

namespace Humweb\Pages\Formats;

use League\CommonMark\CommonMarkConverter;

/**
 * MarkdownFormat.
 */
class MarkdownFormat extends AbstractFormat implements PageFormatContract
{
    /**
     * Markdown parser instance.
     *
     * @var CommonM
     */
    protected $parser;

    /**
     * {@inheritDoc}
     */
    public function toHtml()
    {
        return $this->getParser()->convertToHtml($this->getValue());
    }

    public function render()
    {
        return $this->toHtml();
    }

    /**
     * Returns the markdown parser instance.
     *
     * @return \Michelf\Markdown
     */
    protected function getParser()
    {
        return $this->parser ?: $this->parser = new CommonMarkConverter();
    }
}
