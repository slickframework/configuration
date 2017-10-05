.. title:: Getting started: Slick Configuration

Getting started
---------------

To create a ``ConfigurationInterface`` you should use the ``Slick\Configuration`` factory class
as it really helps you with the creation process.

Basic usage
...........

Lets start by creating a configuration file:

.. code-block:: php

    <?php
    /**
     * App configuration file
     */
    namespace settings;

    $settings = [];
    $settings['application'] = [
        'version' => 'v1.0.0',
        'environment' => 'develop'
    ];
    return $settings;

we save this file as ``./settings.php``.
We are using plain PHP arrays for configuration files. Don't forget to add the
``return`` statement at the end of the file so that the defined array could be
assigned when initializing the driver.

Creating a Configuration
........................

Now we will use the ``Slick\Configuration\Configuration`` factory o create our
``Slick\Configuration\ConfigurationInterface``:

.. code-block:: php

    use Slick\Configuration\Configuration;

    $settings = Configuration::get('settings');

Its really simple.

Retrieving values
.................

Now lets use it.

.. code-block:: php

    print_r($settings->get('application'));

    # the output form above is:
    # Array (
    #    [version] => v1.0.0,
    #    [environment] => develop
    # )

You can set any level of nesting in your configuration array but as you add another
level to the array it becomes harder to use. Please check the example bellow:


.. code-block:: php

    $value = $settings->get('application')['version'];
    // OR
    $appSettings = $settings->get('application');
    $value = $appSettings['version'];

To simplify you ca use a "dot notation" to rich a deeper level.

.. code-block:: php

    $value = $settings->get('application.version');

Default values
..............

It is possible to have a default value when no key is found on a configuration driver. By
default if a key is not found a ``NULL`` is returned but if you specify a value it will
be returned by the ``ConfigurationInterface::get()`` method:

.. code-block:: php

    $value = $settings->get('application.rowsPerPage', 10);
    print $value;

    # the output form above is:
    # 10