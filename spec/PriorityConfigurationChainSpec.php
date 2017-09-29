<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Configuration;

use Slick\Configuration\Common\PriorityList;
use Slick\Configuration\ConfigurationChainInterface;
use Slick\Configuration\ConfigurationInterface;
use Slick\Configuration\PriorityConfigurationChain;
use PhpSpec\ObjectBehavior;

/**
 * PriorityConfigurationChainSpec specs
 *
 * @package spec\Slick\Configuration
 */
class PriorityConfigurationChainSpec extends ObjectBehavior
{

    function its_a_configuration_chain()
    {
        $this->shouldBeAnInstanceOf(ConfigurationChainInterface::class);
    }

    function it_is_initializable_with_an_empty_chain()
    {
        $this->shouldHaveType(PriorityConfigurationChain::class);
    }

    function it_adds_configuration_drivers(ConfigurationInterface $driverA)
    {
        $this->add($driverA)->shouldBe($this->getWrappedObject());
    }

    function it_accepts_priority_when_adding_a_driver(
        ConfigurationInterface $driverA,
        ConfigurationInterface $driverB
    )
    {
        $this->add($driverA ,100);
        $this->add($driverB, 10);
        $this->priorityList()->shouldBeAnInstanceOf(PriorityList::class);
        $this->priorityList()->asArray()[0]->shouldBe($driverB);
    }

    function it_retrieves_a_configuration_value_stored_under_a_key(
        ConfigurationInterface $driverA,
        ConfigurationInterface $driverB
    )
    {
        $this->add($driverA ,100);
        $this->add($driverB, 10);

        $driverA->get('foo', false)->willReturn('fooA');
        $driverB->get('foo', false)->willReturn(false);

        $this->get('foo')->shouldBe('fooA');
        $driverA->get('foo', false)->shouldHaveBeenCalled();
        $driverB->get('foo', false)->shouldHaveBeenCalled();
    }

    function it_retrieves_a_configuration_value_respecting_chain_priority(
        ConfigurationInterface $driverA,
        ConfigurationInterface $driverB
    )
    {
        $this->add($driverA ,100);
        $this->add($driverB, 10);

        $driverA->get('bar', false)->willReturn('fooA');
        $driverB->get('bar', false)->willReturn('fooB');

        $this->get('bar')->shouldBe('fooB');
        $driverA->get('bar', false)->shouldNotHaveBeenCalled();
        $driverB->get('bar', false)->shouldHaveBeenCalled();
    }

    function it_store_requested_values_in_internal_cache(
        ConfigurationInterface $driverA
    )
    {
        $this->add($driverA ,10);
        $driverA->get('bar', false)->willReturn('fooA');

        $this->get('bar')->shouldBe('fooA');

        $this->get('bar')->shouldBe('fooA');
        $driverA->get('bar', false)->shouldHaveBeenCalledTimes(1);
    }

    function it_returns_a_default_value_if_a_given_key_is_not_found()
    {
        $setting = $this->get('baz', true);
        $setting->shouldBeBoolean();
        $setting->shouldBe(true);
    }

    function it_retrieves_a_value_recursively_using_a_dot_notation(
        ConfigurationInterface $driverA
    )
    {
        $driverA->get('first.second.third', false)->willReturn(123);
        $this->add($driverA);

        $this->get('first.second.third')->shouldBe(123);
    }

    function it_sets_a_value_under_a_given_key()
    {
        $this->set('other', 'value')->shouldBe($this->getWrappedObject());
        $this->get('other')->shouldBe('value');
    }

    function it_sets_a_value_recursively_using_a_dot_notation()
    {
        $this->set('value.under.deep', 'path')->shouldBe($this->getWrappedObject());
        $this->get('value.under.deep')->shouldBe('path');
    }
}
