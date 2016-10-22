<?php

namespace Humweb\Pages\Formats;

/**
 * AbstractFormat.
 */
abstract class AbstractFormat implements PageFormatContract
{
    /**
     * The content's value.
     *
     * @var string
     */
    protected $value;

    /**
     * Creates a new content instance.
     *
     * @param string $value
     */
    public function __construct($value = '')
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the content's value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the HTML equivalent of the content.
     *
     * @return string
     */
    abstract public function toHtml();

    public function render()
    {
        return $this->getValue();
    }
}
