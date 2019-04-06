<?php
declare(strict_types=1);

namespace WScore\Validation\Locale;

use InvalidArgumentException;
use WScore\Validation\Interfaces\FilterInterface;

class TypeFilters
{
    /**
     * @var array
     */
    private $typeFilters;

    public function __construct(array $types)
    {
        $this->typeFilters = $types;
    }

    /**
     * @param string $locale
     * @return TypeFilters
     */
    public static function create($locale = 'en'): self
    {
        $type_dir = strlen($locale) === 2
            ? __DIR__ . DIRECTORY_SEPARATOR . $locale
            : $locale;
        if (!is_dir($type_dir)) {
            throw new InvalidArgumentException('message directory not found: ' . $type_dir);
        }
        $type_file = $type_dir . DIRECTORY_SEPARATOR . 'validation.types.php';
        if (!file_exists($type_file)) {
            throw new InvalidArgumentException('message file not found: ' . $type_file);
        }
        /** @noinspection PhpIncludeInspection */
        $types = include($type_file);
        $self = new self($types);

        return $self;
    }

    /**
     * @param string $type
     * @return FilterInterface[]
     */
    public function getFilters($type): array
    {
        if (!isset($this->typeFilters[$type])) {
            throw new InvalidArgumentException('unknown type: ' . $type);
        }
        $rawFilters = $this->typeFilters[$type];
        $filters = [];
        foreach ($rawFilters as $name => $option) {
            $filters[] = new $name($option);
        }

        return $filters;
    }
}