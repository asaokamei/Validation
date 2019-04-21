<?php
declare(strict_types=1);

namespace WScore\Validation\Filters;

use WScore\Validation\Interfaces\FilterInterface;
use WScore\Validation\Interfaces\ResultInterface;

final class ValidateDigits extends AbstractFilter
{
    use ValidateUtf8Trait;

    public function __construct()
    {
    }

    /**
     * @param ResultInterface $input
     * @return ResultInterface|null
     */
    public function apply(ResultInterface $input): ?ResultInterface
    {
        if ($bad = $this->checkUtf8($input)) {
            return $bad;
        }
        $value = $input->value();
        if (!is_numeric($value)) {
            $input->setValue(null);
            return $input->failed(__CLASS__);
        }
        $input->setValue((string)$value);
        return null;
    }
}