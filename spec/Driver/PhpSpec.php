<?php

/**
 * This file is part of Configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Configuration\Driver;

use Slick\Configuration\ConfigurationInterface;
use Slick\Configuration\Driver\Php;
use PhpSpec\ObjectBehavior;
use Slick\Configuration\Exception\FileNotFoundException;
use Slick\Configuration\Exception\ParserErrorException;

/**
 * Php Specs
 *
 * @package spec\Slick\Configuration\Driver
 */
class PhpSpec extends ObjectBehavior
{
    private $file;

    private $dummyFile;

    function let()
    {
        $this->file = __DIR__ . '/fixtures/settings.php';
        $this->dummyFile = __DIR__ . '/fixtures/dummy.txt';
        $this->beConstructedWith($this->file);
    }

    function its_a_configuration_driver_for_php_arrays()
    {
        $this->shouldBeAnInstanceOf(ConfigurationInterface::class);
    }

    function it_is_initializable_with_a_path_to_a_settings_file()
    {
        $this->shouldHaveType(Php::class);
    }

    function it_throws_an_exception_if_settings_file_cannot_be_found()
    {
        $this->beConstructedWith('/_some/missing/file/path.php');
        $this->shouldThrow(FileNotFoundException::class)
            ->duringInstantiation();
    }

    function it_throws_an_exception_if_file_cannot_be_parsed()
    {
        $this->beConstructedWith($this->dummyFile);
        $this->shouldThrow(ParserErrorException::class)
            ->duringInstantiation();
    }

    function it_retrieves_a_configuration_value_stored_under_a_key()
    {
        $this->get('foo')->shouldBe('bar');
    }

    function it_returns_a_default_value_if_a_given_key_is_not_found()
    {
        $setting = $this->get('baz', true);
        $setting->shouldBeBoolean();
        $setting->shouldBe(true);
    }

    function it_retrieves_a_value_recursively_using_a_dot_notation()
    {
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
