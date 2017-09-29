<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Common;

use Traversable;

/**
 * PriorityList
 *
 * @package Slick\Configuration\Common
 */
class PriorityList implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var int
     */
    private $lastPriority = 0;

    /**
     * Inserts the provided element in the right order given the priority
     *
     * The lowest priority will be the first element in the list. If no priority
     * if given, the
     *
     * @param mixed $element
     * @param int  $priority
     *
     * @return PriorityList
     */
    public function insert($element, $priority = 0)
    {
        $data = [];
        $inserted = false;

        foreach ($this->data as $datum) {
            $inserted = $this->tryToInsert($element, $priority, $data, $datum);
            $data[] = ['element' => $datum['element'], 'priority' => $datum['priority']];
            $this->lastPriority = $datum['priority'];
        }

        if (! $inserted) {
            $data[] = ['element' => $element, 'priority' => $priority];
            $this->lastPriority = $priority;
        }

        $this->data = $data;
        return $this;
    }

    /**
     * Tries to insert the provided element in the passed data array
     *
     * @param mixed   $element
     * @param integer $priority
     * @param array   $data
     * @param array   $datum
     *
     * @return bool
     */
    private function tryToInsert($element, $priority, &$data, $datum)
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
    public function asArray()
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
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->asArray());
    }
}