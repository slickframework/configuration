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
use Slick\Configuration\ConfigurationInterface;
use Traversable;

/**
 * PriorityList
 *
 * @package Slick\Configuration\Common
 *
 * @implements ArrayAccess<int|string, ConfigurationInterface>
 * @implements IteratorAggregate<ConfigurationInterface>
 */
class PriorityList implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var array<int|string, array{element: ConfigurationInterface, priority: int}>
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
     * @param ConfigurationInterface $element
     * @param int $priority
     *
     * @return PriorityList<ConfigurationInterface>
     */
    public function insert(ConfigurationInterface $element, int $priority = 0): PriorityList
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
     * @param ConfigurationInterface   $element
     * @param integer $priority
     * @param array<string, array{element: ConfigurationInterface, priority: int}> $data
     * @param array{element: ConfigurationInterface, priority: int} $datum
     *
     * @return bool
     */
    private function tryToInsert(ConfigurationInterface $element, int $priority, array &$data, array $datum): bool
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
     * @return array<int|string, ConfigurationInterface>
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
     * @return ConfigurationInterface
     */
    public function offsetGet(mixed $offset): ?ConfigurationInterface
    {
        return $this->data[$offset] ? $this->data[$offset]['element'] : null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof ConfigurationInterface) {
            $this->insert($value);
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset(mixed $offset): void
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
     * @return Traversable<ConfigurationInterface>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->asArray());
    }
}
