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

**2021-02-24**

- Fixed ErrorHandler accordingly to PHP8 migration guide. Otherwise, error handler is logging too much and doesn't respect configured `error_reporting`.
