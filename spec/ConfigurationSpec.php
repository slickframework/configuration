<?php

/**
 * This file is part of slick/configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Configuration;

use PhpSpec\Exception\Example\FailureException;
use Slick\Configuration\Configuration;
use PhpSpec\ObjectBehavior;
use Slick\Configuration\ConfigurationInterface;
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

    public function __construct()
    {
        Configuration::addPath(__DIR__.'/Driver/fixtures');
    }

    function let()
    {
        $this->settingsFile = 'settings';
        $this->beConstructedWith($this->settingsFile);
    }

    function it_is_initializable_with_a_driver_class_and_options()
    {
        $this->shouldHaveType(Configuration::class);
    }

    function it_initializes_a_php_driver_by_default()
    {
        $chain = $this->initialize();
        $chain->shouldBeAnInstanceOf(PriorityConfigurationChain::class);
        $chain->priorityList()->asArray()[0]->shouldBeAnInstanceOf(Php::class);
    }

    function it_can_determine_the_driver_for_a_given_file_extension()
    {
        $this->beConstructedWith(__DIR__.'/Driver/fixtures/settings.ini');
        $chain = $this->initialize();
        $chain->shouldBeAnInstanceOf(PriorityConfigurationChain::class);
        $chain->priorityList()->asArray()[0]->shouldBeAnInstanceOf(Ini::class);
    }

    function it_throws_an_Exception_when_file_cannot_be_found()
    {
        $this->beConstructedWith();
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('initialize');
    }

    function it_throws_an_Exception_when_file_extension_is_unknown()
    {
        $this->beConstructedWith('settings.cfg');
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('initialize');
    }

    function it_can_be_created_with_multiple_drivers()
    {
        $this->beConstructedWith([
            [null, Configuration::DRIVER_ENV],
            [$this->settingsFile, null, 10],
            [__DIR__.'/Driver/fixtures/settings.ini']
        ]);
        $chain = $this->initialize();
        $chain->shouldBeAnInstanceOf(PriorityConfigurationChain::class);
        $chain->priorityList()->asArray()[0]->shouldBeAnInstanceOf(Environment::class);
        $chain->priorityList()->asArray()[1]->shouldBeAnInstanceOf(Php::class);
        $chain->priorityList()->asArray()[2]->shouldBeAnInstanceOf(Ini::class);

    }

    function it_can_be_created_through_get()
    {
        $this->beConstructedThrough('get', [$this->settingsFile]);
        $this->shouldHaveType(PriorityConfigurationChain::class);
        $object = Configuration::get('settings');
        if ($this->getWrappedObject() !== $object) {
            throw new FailureException("Its not the same object...");
        }
    }

    function it_can_be_created_through_create()
    {
        $this->beConstructedThrough('create', [$this->settingsFile]);
        $this->shouldHaveType(PriorityConfigurationChain::class);
        $object = Configuration::get('settings');
        if ($this->getWrappedObject() === $object) {
            throw new FailureException("Its is the same object...");
        }
    }

    function it_can_reach_nested_settings()
    {
        Configuration::addPath(__DIR__);
        $this->beConstructedWith([
            [null, Configuration::DRIVER_ENV, 10],
            ['settings', Configuration::DRIVER_PHP, 20]
        ]);

        $settings = $this->initialize();

        $settings->get('some.deep.path')->shouldBe('/foo');
        $settings->get('some.deep.value')->shouldBe('bar');
    }

}
