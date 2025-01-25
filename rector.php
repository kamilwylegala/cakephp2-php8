<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;

return RectorConfig::configure()
	->withPaths([
					__DIR__.'/app',
					__DIR__.'/lib',
				])
	->withPhpSets(php80: true);
