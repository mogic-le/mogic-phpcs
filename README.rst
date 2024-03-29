*************************
Mogic PHP coding standard
*************************

A set of rules for `PHP_CodeSniffer`__ and `PHP-CS-Fixer`__.


__ https://github.com/squizlabs/PHP_CodeSniffer
__ https://github.com/PHP-CS-Fixer/PHP-CS-Fixer


Usage
=====

Create your own ``phpcs.xml`` file from this template and adjust it::

  <?xml version="1.0"?>
  <ruleset name="bellevue">
    <description>project-specific coding standard</description>

    <file>Classes</file>
    <file>eid</file>

    <exclude-pattern>*/lib/*</exclude-pattern>

    <rule ref="./vendor/mogic/mogic-phpcs/Mogic/"/>
  </ruleset>


Project with composer
---------------------
The repository is mirrored automatically to Github: https://github.com/mogic-le/mogic-phpcs
The package is also available on packagist: https://packagist.org/packages/mogic/mogic-phpcs

Now run::

  $ composer require --dev mogic/mogic-phpcs:dev-master
  $ ln -s vendor/mogic/mogic-phpcs/.php-cs-fixer.php .php-cs-fixer.php

Then commit ``composer.json`` and ``composer.lock``.

During the build, ``composer install`` needs to be called, which will fetch
the coding standard from git.
To make this work, the build container needs to contain a SSH key that has
read-only access to the coding standards repository.

Example: ``reos-docker -> web-build``



Project without composer dependencies
-------------------------------------
In a project, create a ``composer.json`` file::

  {
      "name": "customer/projectname",
      "description": "FIXME",
      "license": "proprietary",
      "require-dev": {
          "mogic/mogic-phpcs": "dev-master"
      }
  }

Adjust ``Makefile``::

  update-phpcs:
        rm -rf vendor
        composer install
        rm -rf vendor/autoload.php vendor/composer/ vendor/mogic/mogic-phpcs/.git/

Now run ``make update-phpcs`` and git commit the ``vendor/`` dir,
``composer.json`` and ``composer.lock``.


Links
=====
- https://github.com/squizlabs/PHP_CodeSniffer/wiki/Coding-Standard-Tutorial
- https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-ruleset.xml
