.. title:: Configuration Chain: Slick Configuration

Configuration Chain
===================

Starting form v1.2.0, ``Slick\Configuration`` is capable of combine multiple configuration
drivers with a single configuration interface. This allows you to add a more important configuration
source (like environment variables) to be also check when you try to retrieve a configuration value.


Priority Configuration Chain
----------------------------

v1.2.0 added a ``ConfigurationChainInterface`` that allows clients to retrieve a value from a combined
chain of ``ConfigurationInterface`` objects instead of a single configuration source.

It also adds a ``PriorityConfigurationChain`` that implements ``ConfigurationChainInterface`` and its
by default the returned value from ``Configuration::initialize()`` or ``Configuration::get()``
factory methods.

The priority is given by a integer value that determines the order that a key is searched in the chain.
Lower value will be checked first.

Combined configuration
----------------------

Lets try an example. This is our PHP file with an associative array with configuration settings:

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

Now we will create a combined configuration that will have a ``Environment`` driver and a ``Php`` with
the values from the file we have just create.

.. code-block:: php

    use Slick\Configuration\Configuration;

    $settings = Configuration::get([
        [null, Configuration::DRIVER_ENV, 10],
        ['settings', Configuration::DRIVER_PHP, 20]
    ]);

This configuration setup will create the ``Environment`` driver as the first configuration driver that
will be check and then, if not found, the ``Php`` one.

Lets assume that we has define and environment variable as ``APPLICATION_VERSION=v1.2.3`` and lets get
that value from the configuration chain:

.. code-block:: php

    print_r($settings->get('application.version'));

    # the output form above is:
    # v1.2.3

You can combine any number of configuration drivers in one chain and set the priority on with the
search will occur.

.. tip::

    The example shown here is a very simple way of handling environment variables that can be set
    on Docker containers where you some times can't create files.