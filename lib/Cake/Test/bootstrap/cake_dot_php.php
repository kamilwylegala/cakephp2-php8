<?php
/**
 * lib/Cake/Console/cake.php initialize
 */

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$dispatcher = 'Cake' . DS . 'Console' . DS . 'ShellDispatcher.php';
$found = false;
$paths = explode(PATH_SEPARATOR, ini_get('include_path'));

foreach ($paths as $path) {
	if (file_exists($path . DS . $dispatcher)) {
		$found = $path;
		break;
	}
}

if (!$found) {
	$rootInstall = dirname(dirname(dirname(dirname(__FILE__)))) . DS . $dispatcher;
	$composerInstall = dirname(dirname(dirname(__FILE__))) . DS . $dispatcher;

	if (file_exists($composerInstall)) {
		include $composerInstall;
	} elseif (file_exists($rootInstall)) {
		include $rootInstall;
	} else {
		trigger_error('Could not locate CakePHP core files.', E_USER_ERROR);
	}
	unset($rootInstall, $composerInstall);

} else {
	include $found . DS . $dispatcher;
}

// In lib/Cake/Console/cake makes app root path.
$appPath = dirname(__DIR__, 4) . DS . 'app';

new ShellDispatcher([$_SERVER['argv'][0], '-working', $appPath]);

unset($paths, $path, $found, $dispatcher, $appPath);
