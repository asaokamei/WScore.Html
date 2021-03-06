<?php

declare(strict_types=1);

namespace WScore\Html\Tags;

/**
 * Class Html
 * @package WScore\Html
 *
 * @method static $this a(...$contents)
 * @method static $this div(...$contents)
 * @method static $this label(...$contents)
 * @method static $this p(...$contents)
 * @method static $this span(...$contents)
 *
 * @method $this href(string $target)
 * @method $this target(string $target)
 * @method $this placeholder(string $placeholder)
 * @method $this for(string $for)
 */
class Tag
{
    /**
     * @var string
     */
    private $tagName = '';

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $contents = [];

    /**
     * @var bool
     */
    private $hasCloseTag = true;

    /**
     * @var array
     */
    public static $noCloseTags = [
        'img', 'input', 'br', 'hr',
        'area','base','col','embed','link',
        'meta','param','source','track', 'wbr',
    ];

    public function __construct(string $tagName)
    {
        $this->setTagName($tagName);
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }

    /**
     * @param string $tagName
     * @return $this
     */
    protected function setTagName(string $tagName): self
    {
        $this->tagName = $tagName;
        if (in_array($tagName, self::$noCloseTags)) {
            $this->setHasCloseTag(false);
        } else {
            $this->setHasCloseTag(true);
        }
        return $this;
    }

    /**
     * @param string $tagName
     * @return $this
     */
    public static function create(string $tagName): self
    {
        $self = new static($tagName);
        return $self;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return Tag
     */
    public static function __callStatic($name, $arguments)
    {
        $tag = self::create($name);
        if (!empty($arguments)) {
            $tag->setContents(...$arguments);
        }

        return $tag;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return Tag
     */
    public function __call($name, $arguments)
    {
        return $this->set($name, ...$arguments);
    }

    /**
     * @param string $key
     * @param bool|string $value
     * @param string $conn
     * @return $this
     */
    public function set(string $key, $value, string $conn = ' '): self
    {
        if ($value === false) {
            return $this->remove($key);
        }
        if ($value === true) {
            $value = $key;
        }
        return $this->add($key, $value, $conn);
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $attribute) {
            if (method_exists($this, $key)) {
                call_user_func_array([$this, $key], (array) $attribute);
            } else {
                $this->set($key, $attribute);
            }
        }
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $conn
     * @return $this
     */
    public function add(string $key, string $value, string $conn = ' '): self
    {
        if (isset($this->attributes[$key]) && $this->attributes[$key]) {
            $this->attributes[$key] .= $conn . $value;
            return $this;
        }
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return array_key_exists($key, $this->attributes) ?$this->attributes[$key]: null;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function reset(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove(string $key): self
    {
        unset($this->attributes[$key]);
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return ToString::from($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param bool $hasCloseTag
     * @return $this
     */
    public function setHasCloseTag(bool $hasCloseTag): self
    {
        $this->hasCloseTag = $hasCloseTag;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasContents(): bool
    {
        return $this->hasCloseTag && count($this->contents)>0;
    }

    /**
     * @param mixed ...$contents
     * @return $this
     */
    public function setContents(...$contents)
    {
        foreach ($contents as $content) {
            if (!$content) continue;
            $this->contents[] = $content;
        }
        return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function class(string $class): self
    {
        return $this->set('class', $class, ' ');
    }

    /**
     * @param string $style
     * @return $this
     */
    public function style(string $style): self
    {
        return $this->set('style', $style, '; ');
    }

    /**
     * @param string $id
     * @return $this
     */
    public function id(string  $id): self
    {
        return $this->reset('id', $id);
    }

    /**
     * @return bool
     */
    public function hasCloseTag(): bool
    {
        return $this->hasCloseTag;
    }

    /**
     * @return array
     */
    public function getContents(): array
    {
        return $this->contents;
    }
}