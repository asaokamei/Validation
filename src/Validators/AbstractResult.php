<?php
namespace WScore\Validation\Validators;

use WScore\Validation\Interfaces\ResultInterface;
use WScore\Validation\Locale\Messages;

abstract class AbstractResult implements ResultInterface
{
    /**
     * @var null|array
     */
    private $value = [];

    /**
     * @var null|array
     */
    private $originalValue = [];

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var Messages
     */
    private $message;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var Result[]
     */
    protected $children = [];

    /**
     * @var ResultList
     */
    private $parent;

    /**
     * @var array
     */
    private $failed = [];

    /**
     * Result constructor.
     * @param Messages|null $message
     * @param string|array $value
     * @param null|string $name
     */
    public function __construct(?Messages $message, $value, $name = null)
    {
        $this->message = $message;
        $this->value = $this->originalValue = $value;
        $this->name = $name;
    }

    /**
     * @param string $failedAt
     * @param array $options
     * @param string $message
     * @return $this
     */
    public function failed(string $failedAt, array $options = [], string $message = null): ResultInterface
    {
        if ($message === null && $this->message) {
            $message = $this->message->getMessage($failedAt, $options);
        }
        $this->failed[] = [
            'failedAt' => $failedAt,
            'options' => $options,
            'message' => $message
        ];
        $this->isValid = false;
        return $this;
    }

    /**
     * @return string
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|string[]|mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string|string[]|array
     */
    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasChild(string $name): bool
    {
        return isset($this->children[$name]);
    }

    /**
     * @param string|int $name
     * @return ResultInterface
     */
    public function getChild($name): ?ResultInterface
    {
        return $this->children[$name] ?? null;
    }

    /**
     * @return string[]
     */
    public function getErrorMessage()
    {
        if ($this->isValid()) {
            return [];
        }
        $messages = [];
        foreach ($this->failed as $item) {
            $messages[] = $item['message'];
        }
        return $messages;
    }

    /**
     * @return self[]|\iterable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getChildren());
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return count($this->children);
    }

    /**
     * @return ResultInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return ResultList|null
     */
    public function getParent(): ?ResultList
    {
        return $this->parent;
    }

    /**
     * @param ResultList $parent
     */
    public function setParent(ResultList $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return ResultInterface|null
     */
    public function getRoot(): ?ResultInterface
    {
        $root = $this->getParent();
        while($root) {
            if(!$newRoot = $root->getParent()) {
                return $root;
            }
            $root = $newRoot;
        }
        return $root;
    }
}