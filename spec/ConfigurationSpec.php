<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Configuration;

use Slick\Configuration\Configuration;
use PhpSpec\ObjectBehavior;
use Slick\Configuration\Driver\Environment;
use Slick\Configuration\Driver\Ini;
use Slick\Configuration\Driver\Php;
use Slick\Configuration\Exception\InvalidArgumentException;
use Slick\Configuration\PriorityConfigurationChain;

/**
 * ConfigurationSpec specs
 *
 * @package spec\Slick\Configuration
 */
class ConfigurationSpec extends ObjectBehavior
{
    private $settingsFile;

    function let()
    {
        $this->settingsFile = __DIR__.'/Driver/fixtures/settings.php';
        $this->beConstructedWith([$this->settingsFile]);
    }

    function it_is_initializable_with_a_driver_class_and_options()
    {
        $this->shouldHaveType(Configuration::class);
    }

    function it_initializes_a_php_driver_by_default()
    {
        $chain = $this->initialize();
        $chain->shouldBeAnInstanceOf(PriorityConfigurationChain::class);
        $chain->queue()->current()->shouldBeAnInstanceOf(Php::class);
    }

    function it_can_determine_the_driver_for_a_given_file_extension()
    {
        $this->beConstructedWith( [__DIR__.'/Driver/fixtures/settings.ini']);
        $chain = $this->initialize();
        $chain->shouldBeAnInstanceOf(PriorityConfigurationChain::class);
        $chain->queue()->current()->shouldBeAnInstanceOf(Ini::class);
    }

    function it_throws_an_Exception_when_file_cannot_be_found()
    {
        $this->beConstructedWith([]);
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('initialize');
    }

    function it_throws_an_Exception_when_file_extension_is_unknown()
    {
        $this->beConstructedWith(['settings.cfg']);
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('initialize');
    }

    function it_can_be_created_with_multiple_drivers()
    {
        $this->beConstructedWith([
            [null, Configuration::DRIVER_ENV],
            [$this->settingsFile],
            [__DIR__.'/Driver/fixtures/settings.ini']
        ]);
        $chain = $this->initialize();
        $chain->shouldBeAnInstanceOf(PriorityConfigurationChain::class);
        $chain->queue()->current()->shouldBeAnInstanceOf(Environment::class);
        $chain->queue()->next();
        $chain->queue()->current()->shouldBeAnInstanceOf(Php::class);
        $chain->queue()->next();
        $chain->queue()->current()->shouldBeAnInstanceOf(Ini::class);

    }

}
