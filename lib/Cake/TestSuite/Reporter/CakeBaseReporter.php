<?php
/**
 * CakeBaseReporter contains common functionality to all cake test suite reporters.
 *
 * CakePHP(tm) Tests <https://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.3
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;

/**
 * CakeBaseReporter contains common reporting features used in the CakePHP Test suite
 *
 * @package       Cake.TestSuite.Reporter
 */
class CakeBaseReporter extends \PHPUnit\TextUI\ResultPrinter {

/**
 * Headers sent
 *
 * @var bool
 */
	protected $_headerSent = false;

/**
 * Array of request parameters. Usually parsed GET params.
 *
 * @var array
 */
	public $params = array();

/**
 * Character set for the output of test reporting.
 *
 * @var string
 */
	protected $_characterSet;

/**
 * Does nothing yet. The first output will
 * be sent on the first test start.
 *
 * ### Params
 *
 * - show_passes - Should passes be shown
 * - plugin - Plugin test being run?
 * - core - Core test being run.
 * - case - The case being run
 * - codeCoverage - Whether the case/group being run is being code covered.
 *
 * @param string $charset The character set to output with. Defaults to UTF-8
 * @param array $params Array of request parameters the reporter should use. See above.
 */
	public function __construct($charset = 'utf-8', $params = array()) {
		if (!$charset) {
			$charset = 'utf-8';
		}
		$this->_characterSet = $charset;
		$this->params = $params;

		parent::__construct();
	}

/**
 * Retrieves a list of test cases from the active Manager class,
 * displaying it in the correct format for the reporter subclass
 *
 * @return mixed
 */
	public function testCaseList() {
		$testList = CakeTestLoader::generateTestList($this->params);
		return $testList;
	}

/**
 * Paints the start of the response from the test suite.
 * Used to paint things like head elements in an html page.
 *
 * @return void
 */
	public function paintDocumentStart() {
	}

/**
 * Paints the end of the response from the test suite.
 * Used to paint things like </body> in an html page.
 *
 * @return void
 */
	public function paintDocumentEnd() {
	}

/**
 * Paint a list of test sets, core, app, and plugin test sets
 * available.
 *
 * @return void
 */
	public function paintTestMenu() {
	}

/**
 * Get the baseUrl if one is available.
 *
 * @return string The base URL for the request.
 */
	public function baseUrl() {
		if (!empty($_SERVER['PHP_SELF'])) {
			return $_SERVER['PHP_SELF'];
		}
		return '';
	}

/**
 * Print result
 *
 * @param TestResult $result The result object
 * @return void
 */
	public function printResult(TestResult $result): void {
		$this->paintFooter($result);
	}

/**
 * Paint result
 *
 * @param TestResult $result The result object
 * @return void
 */
	public function paintResult(TestResult $result) {
		$this->paintFooter($result);
	}

/**
 * An error occurred.
 *
 * @param \PHPUnit\Framework\Test $test The test to add an error for.
 * @param Throwable $e The exception object to add.
 * @param float $time The current time.
 * @return void
 */
	public function addError(\PHPUnit\Framework\Test $test, \Throwable $e, float $time): void {
		$this->paintException($e, $test);
	}

/**
 * A failure occurred.
 *
 * @param \PHPUnit\Framework\Test $test The test that failed
 * @param AssertionFailedError $e The assertion that failed.
 * @param float $time The current time.
 * @return void
 */
	public function addFailure(\PHPUnit\Framework\Test $test, AssertionFailedError $e, float $time): void {
		$this->paintFail($e, $test);
	}

/**
 * Incomplete test.
 *
 * @param \PHPUnit\Framework\Test $test The test that was incomplete.
 * @param Throwable $e The incomplete exception
 * @param float $time The current time.
 * @return void
 */
	public function addIncompleteTest(\PHPUnit\Framework\Test $test, \Throwable $e, float $time): void {
		$this->paintSkip($e, $test);
	}

/**
 * Skipped test.
 *
 * @param \PHPUnit\Framework\Test $test The test that failed.
 * @param Throwable $e The skip object.
 * @param float $time The current time.
 * @return void
 */
	public function addSkippedTest(\PHPUnit\Framework\Test $test, Throwable $e, float $time): void {
		$this->paintSkip($e, $test);
	}

/**
 * A test suite started.
 *
 * @param TestSuite $suite The suite to start
 * @return void
 */
	public function startTestSuite(TestSuite $suite): void {
		if (!$this->_headerSent) {
			echo $this->paintHeader();
		}
		echo __d('cake_dev', 'Running  %s', $suite->getName()) . "\n";
	}

/**
 * A test suite ended.
 *
 * @param TestSuite $suite The suite that ended.
 * @return void
 */
	public function endTestSuite(TestSuite $suite): void {
	}

/**
 * A test started.
 *
 * @param \PHPUnit\Framework\Test $test The test that started.
 * @return void
 */
	public function startTest(\PHPUnit\Framework\Test $test): void {
	}

/**
 * A test ended.
 *
 * @param \PHPUnit\Framework\Test $test The test that ended
 * @param float $time The current time.
 * @return void
 */
	public function endTest(\PHPUnit\Framework\Test $test, float $time): void {
		$this->numAssertions += $test->getNumAssertions();
		if ($test->hasFailed()) {
			return;
		}
		$this->paintPass($test, $time);
	}
}
