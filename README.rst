*************************
Mogic PHP coding standard
*************************

Used in several projects:

- bellevue
- fio websitebuilder


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
Add the ``repositories`` to composer.json::

  "repositories": [
      {
          "type": "vcs",
          "url": "git@gitlab.mogic.com:mogic/mogic-phpcs.git"
      }
  ]

Now run::

  $ composer require mogic/mogic-phpcs:dev-master

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
      },
      "repositories": [
          {
              "type": "vcs",
              "url": "git@gitlab.mogic.com:mogic/mogic-phpcs.git"
          }
      ]
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
