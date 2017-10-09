.. title:: Contributing: Slick Cache

Contributing
============

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on `Github`_.


Pull requests
-------------
- `PSR-2 Coding Standard`_ - Check the code style with ``$ composer check-style`` and fix it with ``$ composer fix-style``.

- **Add tests!** - Your patch won't be accepted if it doesn't have tests.

- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow `SemVer v2.0.0`_. Randomly breaking public APIs is not an option.

- **Create feature branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please `squash them`_ before submitting.


Running tests
-------------
We use `phpspec`_ for unit tests.

.. code-block:: bash

    # unit tests
    $ vendor/bin/phpspec run -fdot


Security
--------

If you discover any security related issues, please email
`slick.framework@gmail.com <mailto:slick.framework@gmail.com>`_ instead of using the issue tracker.

.. _Github: https://github.com/slickframework/configuration
.. _PSR-2 Coding Standard: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
.. _PHP Code Sniffer: http://pear.php.net/package/PHP_CodeSniffer
.. _SemVer v2.0.0: http://semver.org
.. _phpspec: http://www.phpspec.net
.. _squash them: http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages