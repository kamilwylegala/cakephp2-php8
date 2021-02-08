<?php

include "lib/Cake/Console/ShellDispatcher.php";

$shellDispatcher = new ShellDispatcher([
	realpath(__DIR__ . "/lib/Cake/Console/cake.php"),
	"-working",
	dirname(__DIR__) . "/",
]);
