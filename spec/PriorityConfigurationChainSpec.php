<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Configuration;

use Slick\Configuration\Common\PriorityList;
use Slick\Configuration\Configuration;
use Slick\Configuration\ConfigurationChainInterface;
use Slick\Configuration\ConfigurationInterface;
use Slick\Configuration\Driver\Environment;
use Slick\Configuration\Driver\Php;
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
    ) {
        $this->add($driverA, 100);
        $this->add($driverB, 10);

        $driverA->asArray()->willReturn(['foo' => 'fooA']);
        $driverB->asArray()->willReturn([]);

        $this->get('foo')->shouldBe('fooA');
        $driverA->asArray()->shouldHaveBeenCalled();
        $driverB->asArray()->shouldHaveBeenCalled();
    }

    function it_retrieves_a_configuration_value_respecting_chain_priority(
        ConfigurationInterface $driverA,
        ConfigurationInterface $driverB
    )
    {
        $this->add($driverA ,100);
        $this->add($driverB, 10);

        $driverA->asArray()->willReturn(['bar' => 'fooA']);
        $driverB->asArray()->willReturn(['bar' => 'fooB']);

        $this->get('bar')->shouldBe('fooB');
        $driverA->asArray()->shouldHaveBeenCalled();
        $driverB->asArray()->shouldHaveBeenCalled();
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
        $driverA->asArray()->willReturn([
            'first' => [
                'second' => ['third' => 123]
            ]
        ]);
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

    function it_should_merge_all_settings_with_prioriry()
    {
        $this->add(new Environment(), 10)->add(new Php(__DIR__.'/settings.php'), 20);
        $this->get('testenv')->shouldBe([
            'enabled' => true,
            'mode' => "develop,debug,coverage",
        ]);
    }
}
