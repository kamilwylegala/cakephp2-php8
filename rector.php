<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {

	$parameters = $containerConfigurator->parameters();

	$parameters->set(Option::AUTOLOAD_PATHS, [

//		// it seems that if we set this option, it doesn't autoload PhpUnit, so we add it back in
		__DIR__ . "/vendors/autoload.php",

//		// bootstrap stuff like Cake constants
		__DIR__ . "/bootstrap_rector.php",

		// we can't just give it the Cake directory because there are some ambiguous classes
		// e.g. there is a real AppShell and a version in the console templates dir

		__DIR__ . "/app/View/Helper/AppHelper.php",
		__DIR__ . "/app/Controller/AppController.php",

		__DIR__ . "/lib/Cake/Cache/",
		__DIR__ . "/lib/Cake/Config/",
		__DIR__ . "/lib/Cake/Configure/",

		__DIR__ . "/lib/Cake/Console/Command/",
		__DIR__ . "/lib/Cake/Console/Helper/",
		__DIR__ . "/lib/Cake/Console/ConsoleErrorHandler.php",
		__DIR__ . "/lib/Cake/Console/ConsoleInput.php",
		__DIR__ . "/lib/Cake/Console/ConsoleInputArgument.php",
		__DIR__ . "/lib/Cake/Console/ConsoleInputOption.php",
		__DIR__ . "/lib/Cake/Console/ConsoleInputSubcommand.php",
		__DIR__ . "/lib/Cake/Console/ConsoleOptionParser.php",
		__DIR__ . "/lib/Cake/Console/ConsoleOutput.php",
		__DIR__ . "/lib/Cake/Console/HelpFormatter.php",
		__DIR__ . "/lib/Cake/Console/Shell.php",
		__DIR__ . "/lib/Cake/Console/ShellDispatcher.php",
		__DIR__ . "/lib/Cake/Console/TaskCollection.php",

		__DIR__ . "/lib/Cake/Controller/",
		__DIR__ . "/lib/Cake/Core/",
		__DIR__ . "/lib/Cake/Error/",
		__DIR__ . "/lib/Cake/Event/",
		__DIR__ . "/lib/Cake/I18n/",
		__DIR__ . "/lib/Cake/Log/",
		__DIR__ . "/lib/Cake/Model/",
		__DIR__ . "/lib/Cake/Network/",
		__DIR__ . "/lib/Cake/Routing/",

		__DIR__ . "/lib/Cake/Test/Case",
		__DIR__ . "/lib/Cake/Test/Case/Console/Command/BakeShellTest.php",
		__DIR__ . "/lib/Cake/Test/Case/Console/Command/Task/ControllerTaskTest.php",
		__DIR__ . "/lib/Cake/Test/Fixture",

		__DIR__ . "/lib/Cake/TestSuite/",

		__DIR__ . "/lib/Cake/Utility/",
		__DIR__ . "/lib/Cake/View/",
	]);

	// tests
	$parameters->set(Option::PATHS, [
		__DIR__ . "/lib/Cake/Test/Case/",
		__DIR__ . "/lib/Cake/TestSuite/CakeTestCase.php",
	]);

	$parameters->set(Option::SETS, [SetList::PHPUNIT_80]);
};
