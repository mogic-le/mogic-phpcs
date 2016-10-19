*************************
Mogic PHP coding standard
*************************

Used in several projects:

- bellevue
- fio websitebuilder


Usage
=====
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

Create your own ``phpcs.xml`` file from this template and adjust it::

  <?xml version="1.0"?>
  <ruleset name="bellevue">
    <description>project-specific coding standard</description>

    <file>Classes</file>
    <file>eid</file>

    <exclude-pattern>*/lib/*</exclude-pattern>

    <rule ref="./vendor/mogic/mogic-phpcs/Mogic/"/>
  </ruleset>


Links
=====
- https://github.com/squizlabs/PHP_CodeSniffer/wiki/Coding-Standard-Tutorial
- https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-ruleset.xml
