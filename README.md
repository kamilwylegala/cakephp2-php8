# Fork of CakePHP 2 with support for PHP8

~~For original README content please check original repository: https://github.com/cakephp/cakephp/tree/2.x~~

Unfortunately branch 2.x in original repository was taken down.

## Why I created this fork? 🤔

CakePHP 2 stopped getting updates in the end of 2019 (AFAIR). Unfortunately in my case it's too expensive to migrate to newer versions of CakePHP. I started migrating to Symfony framework, but I still use ORM from CakePHP (and actually I like it). So in order to keep up with the newest PHP versions I decided to create fork of the framework.

## Why you should NOT use? ⛔

- Intention of this fork is to support PHP 8.*. Fork is not going to receive new features. Instead, fork is going to get minimal set of patches to comply with newer versions of PHP.
- If for example you're still on 5.6 or 7.0, you should **not** use this fork. Original `cakephp/cakephp` works perfectly fine on all PHP 7.* versions. You should migrate to newer versions of PHP and keep using original code. Once your application is battle tested on production I suggest migrating to PHP 8.

## When you could use this fork? ✅

Only prerequisite is to have your application already on PHP 7.4. Upgrade project to PHP 8.0 and replace CakePHP with this fork.

### Migration

Here are steps I took to migrate my project through all versions to PHP 8.1, maybe it can inspire you:

1. Decouple your tests from `CakeTestCase` and other utilities that are coupled to old PHPUnit version.
2. Once decoupled you can upgrade PHPUnit to the newest version accordingly to your PHP version.
3. Start upgrading gradually to newer versions of PHP. CakePHP 2 works perfectly fine on 7.0 - 7.4.
4. Once you're on 7.4 you can switch to 8 and this fork.

## Before using this fork ⚠️

- Tests of CakePHP framework aren't refactored yet to support PHP 8. Main issue is old version of PHPUnit that is tightly coupled to framework's tests. Issue for fixing this situation is here: https://github.com/kamilwylegala/cakephp2-php8/issues/7
- Due to lack of tests ☝️ - **you need to rely** on tests in your application after integrating with this fork.
- If after integration you spot any issues related to framework please let me know by creating an issue or pull request with fix.

## Installation

This repository **is not** available in packagist, therefore your project's `composer.json` must be changed to point to custom repository.

Example configuration:
```
{
	"require": {
		"cakephp/cakephp": "dev-master as 2.10.24",
	},
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kamilwylegala/cakephp2-php8"
        }
    ]
}
```

It means that composer will look at `master` branch of repository configured under `repositories` to resolve update of `cakephp/cakephp` package.

## Changelog

### 2023-06-02

- Fixed PHP 8.2 deprecation notices in CakeEvent: `Creation of dynamic property ... is deprecated.`

### 2023-02-19

- Fixed PHP 8.1 MySQL test suite.

### 2023-02-11

- Fixed PostgreSQL test suite.

### 2023-01-30

- `PaginatorHelper` fix.

### 2023-01-22

- Fixed views cache when relative time is specified.

### 2023-01-11

- Fixed test suite to run under PHPUnit 9.5 and PHP8. Big kudos to @tenkoma :clap:

### 2022-10-20

- `MailTransport` fix.

### 2022-10-08

- Support for `full_path` when uploading a file, PHP 8.1 only.

### 2022-09-27

- Fixed multiple `CREATE UNIQUE INDEX` statements from schema shell that did not work on PostgreSQL.

### 2022-03-08

- Fixed passing `params["pass"]` argument to `invokeArgs` when resolving controller action - `array_values` used to avoid problems with named parameters.

### 2022-03-03

- Removed `String` class.

### 2022-03-02

- Fixed `ConsoleErrorHandler::handleError` to respect error suppression.

### 2022-01-31

- Fixed `Folder->read`, `array_values` is used to remove keys to prevent usign named arguments in `call_user_func_array`

### 2022-01-16

- Fix Shell `ReflectionMethod::__construct` default null argument in hasMethod

### 2022-01-15

- Readme file update - more explicit content.

### 2022-01-04

- Fixed more deprecation notices
    - `strtoupper` + `converting false to array` in Mysql.php
    - `preg_match` where `$subject = null` in CakeRoute.php
    - `strtoupper` in DboSource.php
    - Check history for details ☝️


### 2021-12-20

- Fixed deprecation notices in PHP 8.1 for production code implementations:
    - `ArrayAccess`
    - `Countable`
    - `IteratorAggregate`
- PHP 8.0 requirement in composer.json
- **Warning:** Tests are not updated, Cake's tests rely on old version of PHPUnit so running them may show a lot of deprecations notices. Added issue to cover it: #7

### 2021-02-24

- Fixed ErrorHandler accordingly to PHP8 migration guide. Otherwise, error handler is logging too much and doesn't respect configured `error_reporting`.
