<?php
declare(strict_types=1);

namespace WScore\Validator\Filters;

use WScore\Validator\Interfaces\ResultInterface;

class DefaultValue extends AbstractFilter
{
    /**
     * @var mixed
     */
    private $default;

    /**
     * DefaultValue constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (!is_array($options)) {
            $this->default = $options;
        } else {
            $this->default = array_key_exists('default', $options)
                ? $options['default'] : null;
        }
    }

    /**
     * @param ResultInterface $input
     * @return ResultInterface|null
     */
    public function apply(ResultInterface $input): ?ResultInterface
    {
        $value = $input->value();
        if (is_object($value)) {
            return null;
        }
        if ('' !== (string) $value) {
            return null;
        }
        $input->setValue($this->default);
        return null;
    }

    /**
     * @param mixed $default
     * @return DefaultValue
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }
}