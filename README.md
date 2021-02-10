# CakePHP fork

This is a fork of version 2 of the CakePHP project with the goal of keeping the framework running on newer versions of PHP. The official 2.x branch is EOL and the framework tests cannot be run on versions of PHPUnit above 5. This fork is therefore necessary to update PHPUnit to newer versions and therefore support PHP 8 (hopefully).

[Original CakePHP 2 documentation](https://book.cakephp.org/2/en/contributing/documentation.html)

# Support
This repository is not intended to be used by developers from outside our team and will eventually be moved to a private repository/package. We will not address PRs or issues from outside our team.

# Changes made to date
- Deleted ControllerTestCase and sub-classes
- Updated PHPUnit to version 6

# Roadmap
- Update PHPUnit to latest version (currently v10)
- Execute framework tests on PHP 7.4
- Execute framework tests on PHP 8.0
- (BREAKING) Add all framework classes to composer classmap and remove App::uses()
- (BREAKING) Re-arrange framework code into separate `src`, `test` folders, and remove the `app` template folder from this repository
- Update the core test suite so that it can be run using PHPUnit's standard test runner and doesn't require Cake's test shell
- Delete parts of the framework that we do not use / are unlikely to ever use (e.g. Postgres DboSource)

#Running tests

Run the tests in a CentOS VM. You will need the following in addition to our basic PHP set-up.

```
sudo yum -y install glibc-locale-source glibc-langpack-en
sudo localedef -v -c -i es_ES -f UTF-8 es_ES
sudo localedef -v -c -i de_DE -f UTF-8 de_DE
```

Running tests:
`./tests AllTests`

To run a single test, you need to use the "Cake namespace" of the class under test. e.g. to run `lib/Cake/Model/Datasource/CakeSessionTest`, you would run:

`./tests Model/Datasource/CakeSession`

By default, the tests run with an sqlite database, to run for MySQL, you need to configure a database connection in `app/Config/database.php` and make sure the following empty databases have been created:
`cakephp_test`, `cakephp_test2`, `cakephp_test3`, and then set the env var `DB` to `mysql`, i.e.:
`DB=mysql ./tests AllTests`
