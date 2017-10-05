Slick Configuration
==========================

``slick/configuration`` is a simple package that deals with configuration files.
It has a very simple interface that you can use to set your own configuration
drivers. By default it uses the PHP arrays for configuration as it does not need
any parser and therefore is more performance friendly.

Installation
------------

``slick/configuration`` is a php 5.6+ library that you’ll have in your project development
environment. Before you begin, ensure that you have PHP 5.6 or higher installed.

You can install ``slick/configuration`` with all its dependencies through Composer. Follow
instructions on the `composer website`_ if you don’t have it installed yet.

You can use this Composer command to install ``slick/configuration``:

.. code-block:: bash

    $ composer require slick/configuration


.. toctree::
    :hidden:
    :maxdepth: 2

    manual/getting-started
    manual/multiple-configurations
    manual/contrib
    manual/license

.. _PSR-6: http://www.php-fig.org/psr/psr-6/
.. _PSR-16: http://www.php-fig.org/psr/psr-16/
.. _composer website: https://getcomposer.org/download/