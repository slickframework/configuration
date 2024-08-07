<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Configuration\Driver;

use Slick\Configuration\ConfigurationInterface;
use Slick\Configuration\Driver\Environment;
use PhpSpec\ObjectBehavior;

/**
 * EnvironmentSpec specs
 *
 * @package spec\Slick\Configuration\Driver
 */
class EnvironmentSpec extends ObjectBehavior
{

    function its_a_configuration_driver()
    {
        $this->shouldBeAnInstanceOf(ConfigurationInterface::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Environment::class);
    }

    function it_reads_environment_values()
    {
        $this->get('testenv.mode')->shouldBe('develop,debug,coverage');
    }

    function it_returns_a_default_value_if_a_given_key_is_not_found()
    {
        $setting = $this->get('baz', true);
        $setting->shouldBeBoolean();
        $setting->shouldBe(true);
    }

    function it_retrieves_a_value_recursively_using_a_dot_notation()
    {
        putenv("FIRST_SECOND_THIRD=123");
        $this->get('first.second.third')->shouldBe("123");
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
