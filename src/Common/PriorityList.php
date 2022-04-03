<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Common;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * PriorityList
 *
 * @package Slick\Configuration\Common
 */
class PriorityList implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var array
     */
    private array $data = [];

    /**
     * @var int
     */
    private int $lastPriority = 0;

    /**
     * Inserts the provided element in the right order given the priority
     *
     * The lowest priority will be the first element in the list.
     *
     * @param mixed $element
     * @param int $priority
     *
     * @return PriorityList
     */
    public function insert(mixed $element, int $priority = 0): static
    {
        $data = [];
        $inserted = false;
        $priority = $priority === 0 ? $this->lastPriority : $priority;

        foreach ($this->data as $datum) {
            $inserted = $this->tryToInsert($element, $priority, $data, $datum);
            $data[] = ['element' => $datum['element'], 'priority' => $datum['priority']];
            $this->lastPriority = $datum['priority'];
        }

        if (!$inserted) {
            $data[] = ['element' => $element, 'priority' => $priority];
            $this->lastPriority = $priority;
        }

        $this->data = $data;
        return $this;
    }

    /**
     * Tries to insert the provided element in the given data array
     *
     * @param mixed   $element
     * @param integer $priority
     * @param array $data
     * @param array $datum
     *
     * @return bool
     */
    private function tryToInsert(mixed $element, int $priority, array &$data, array $datum): bool
    {
        $inserted = false;
        if ($datum['priority'] > $priority) {
            $data[] = ['element' => $element, 'priority' => $priority];
            $inserted = true;
        }
        return $inserted;
    }

    /**
     * Returns the inserted elements in the order given by priority as an array
     *
     * @return array
     */
    public function asArray(): array
    {
        $data = [];
        foreach ($this->data as $datum) {
            $data[] = $datum['element'];
        }
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet(mixed $offset)
    {
        return $this->data[$offset];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet(mixed $offset, mixed $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset(mixed $offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): Traversable|array|ArrayIterator
    {
        return new ArrayIterator($this->asArray());
    }
}
