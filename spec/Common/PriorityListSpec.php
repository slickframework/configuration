<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Configuration\Common;

use Slick\Configuration\Common\PriorityList;
use PhpSpec\ObjectBehavior;

/**
 * PriorityListSpec specs
 *
 * @package spec\Slick\Configuration\Common
 */
class PriorityListSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PriorityList::class);
    }

    function it_can_be_used_as_an_array()
    {
        $this->shouldBeAnInstanceOf(\ArrayAccess::class);
    }

    function its_an_iterable()
    {
        $this->shouldImplement(\IteratorAggregate::class);
    }

    function it_can_insert_objects()
    {
        $this->insert((object) [], 10)->shouldBe($this->getWrappedObject());
    }

    function it_can_be_converted_to_array()
    {
        $this->asArray()->shouldBeArray();
    }

    function it_adds_elements_with_a_given_priority()
    {

        $this->insert(1, 10)
            ->insert(2, 20)
            ->insert(3, 15);
        $array = $this->asArray();
        $array[2]->shouldBe(2);
    }

    function it_can_be_counted()
    {
        $this->insert(1, 10)
            ->insert(2, 20)
            ->insert(3, 15);
        $this->shouldBeAnInstanceOf(\Countable::class);
        $this->count()->shouldBe(3);
    }
}
