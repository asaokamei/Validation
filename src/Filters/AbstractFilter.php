<?php
declare(strict_types=1);

namespace WScore\Validation\Filters;

use WScore\Validation\Interfaces\FilterInterface;
use WScore\Validation\Interfaces\ResultInterface;

abstract class AbstractFilter implements FilterInterface
{
    private $addType = FilterInterface::ADD_APPEND;

    public function getAddType(): string
    {
        return $this->addType;
    }

    /**
     * @param string $addType
     */
    protected function setAddType(string $addType): void
    {
        $this->addType = $addType;
    }

    /**
     * returns name of the filter;
     * validation can have only one filter with the same name.
     *
     * @return string
     */
    public function getFilterName(): string
    {
        return get_class($this);
    }

    /**
     * @param ResultInterface $result
     * @param array $option
     * @param string $messages
     * @return ResultInterface
     */
    protected function failed(ResultInterface $result, $option = [], $messages = null)
    {
        return $result->failed(get_class($this), $option, $messages);
    }

    protected function isEmpty($value): bool
    {
        if (is_array($value)) {
            if (empty($value)) {
                return true;
            }
            return false;
        }
        if ($value === null || (string) $value === '') {
            return true;
        }
        return false;
    }

    protected function hasValue($value): bool
    {
        return !$this->isEmpty($value);
    }
}