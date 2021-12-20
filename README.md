# Fork of CakePHP 2 with support for PHP8

For original README content please check original repository: https://github.com/cakephp/cakephp/tree/2.x

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

### 2021-12-20

- Fixed deprecation notices in PHP 8.1 for production code implementations:
    - `ArrayAccess`
    - `Countable`
    - `IteratorAggregate`
- PHP 8.0 requirement in composer.json
- **Warning:** Tests are not updated, Cake's tests rely on old version of PHPUnit so running them may show a lot of deprecations notices. Added issue to cover it: #7

### 2021-02-24

- Fixed ErrorHandler accordingly to PHP8 migration guide. Otherwise, error handler is logging too much and doesn't respect configured `error_reporting`.
