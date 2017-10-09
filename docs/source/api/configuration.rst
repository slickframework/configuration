.. title:: Configuration reference: Slick Configuration

Configuration interface
=======================

.. php:namespace:: Slick\Configuration

.. php:class:: ConfigurationInterface

    ConfigurationInterface, defines a configuration driver

.. php:method:: get($key [, $default = NULL])

    Returns the value store with provided key or the default value.

    :param string $key: The key used to store the value in configuration.
    :param mixed $default: The default value if no value was stored.
    :returns: The configuration stored value or the default if not found.

.. php:method:: get($key, $value)

    Set/Store the provided value with a given key.

    :param string $key: The key used to store the value in configuration.
    :param mixed $value: The value to store under the provided key.
    :returns: Self instance for method call chains.