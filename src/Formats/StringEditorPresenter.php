<?php

namespace Humweb\Pages\Formats;

use Humweb\Core\Support\Traits\ConfigTrait;

/**
 * StringEditorPresenter.
 */
class StringEditorPresenter implements EditorPresenterContract
{
    use ConfigTrait;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $value;

    /**
     * Creates a new content instance.
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     */
    public function __construct($name, $value, $options = [])
    {
        $this->configFill($options);
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Returns the content's value.
     *
     * @return string
     */
    public function render()
    {
        // TODO: Implement render() method.
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
