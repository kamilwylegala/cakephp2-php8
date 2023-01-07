<?php
/**
 * ErrorHandlerTest file
 *
 * CakePHP(tm) Tests <https://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Case.Error
 * @since         CakePHP(tm) v 1.2.0.5432
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('ErrorHandler', 'Error');
App::uses('Controller', 'Controller');
App::uses('Router', 'Routing');
App::uses('Debugger', 'Utility');

/**
 * A faulty ExceptionRenderer to test nesting.
 */
class FaultyExceptionRenderer extends ExceptionRenderer {

/**
 * Dummy rendering implementation.
 *
 * @return void
 * @throws Exception
 */
	public function render() {
		throw new Exception('Error from renderer.');
	}

}

/**
 * ErrorHandlerTest class
 *
 * @package       Cake.Test.Case.Error
 */
class ErrorHandlerTest extends CakeTestCase {

	protected $_restoreError = false;

/**
 * setup create a request object to get out of router later.
 *
 * @return void
 */
	public function setUp() : void {
		parent::setUp();
		App::build(array(
			'View' => array(
				CAKE . 'Test' . DS . 'test_app' . DS . 'View' . DS
			)
		), App::RESET);
		Router::reload();

		$request = new CakeRequest(null, false);
		$request->base = '';
		Router::setRequestInfo($request);
		Configure::write('debug', 2);

		if (CakeLog::stream('stdout') !== false) {
			CakeLog::disable('stdout');
		}
		if (CakeLog::stream('stderr') !== false) {
			CakeLog::disable('stderr');
		}
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() : void {
		parent::tearDown();
		if ($this->_restoreError) {
			restore_error_handler();
		}

		if (CakeLog::stream('stdout') !== false) {
			CakeLog::enable('stdout');
		}
		if (CakeLog::stream('stderr') !== false) {
			CakeLog::enable('stderr');
		}
	}

/**
 * test error handling when debug is on, an error should be printed from Debugger.
 *
 * @return void
 */
	public function testHandleErrorDebugOn() {
		set_error_handler('ErrorHandler::handleError');
		$this->_restoreError = true;

		Debugger::getInstance()->output('html');

		ob_start();
		$wrong .= '';
		$result = ob_get_clean();

		$this->assertMatchesRegularExpression('/<pre class="cake-error">/', $result);
		$this->assertMatchesRegularExpression('/<b>Warning<\/b>/', $result);
		$this->assertMatchesRegularExpression('/variable\s+\$wrong/', $result);
	}

/**
 * provides errors for mapping tests.
 *
 * @return void
 */
	public static function errorProvider() {
		return array(
			array(E_USER_NOTICE, 'Notice'),
			array(E_USER_WARNING, 'Warning'),
		);
	}

/**
 * test error mappings
 *
 * @dataProvider errorProvider
 * @return void
 */
	public function testErrorMapping($error, $expected) {
		set_error_handler('ErrorHandler::handleError');
		$this->_restoreError = true;

		Debugger::getInstance()->output('html');

		ob_start();
		trigger_error('Test error', $error);

		$result = ob_get_clean();
		$this->assertMatchesRegularExpression('/<b>' . $expected . '<\/b>/', $result);
	}

/**
 * test error prepended by @
 *
 * @return void
 */
	public function testErrorSuppressed() {
		set_error_handler('ErrorHandler::handleError');
		$this->_restoreError = true;

		ob_start();
		//@codingStandardsIgnoreStart
		@include 'invalid.file';
		//@codingStandardsIgnoreEnd
		$result = ob_get_clean();
		$this->assertTrue(empty($result));
	}

/**
 * Test that errors go into CakeLog when debug = 0.
 *
 * @return void
 */
	public function testHandleErrorDebugOff() {
		Configure::write('debug', 0);
		Configure::write('Error.trace', false);
		if (file_exists(LOGS . 'error.log')) {
			unlink(LOGS . 'error.log');
		}

		set_error_handler('ErrorHandler::handleError');
		$this->_restoreError = true;

		$out .= '';

		$result = file(LOGS . 'error.log');
		$this->assertEquals(1, count($result));
		$this->assertMatchesRegularExpression(
			'/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} Warning: Warning \(2\): Undefined variable\s+\$out in \[.+ line \d+\]$/',
			$result[0]
		);
		if (file_exists(LOGS . 'debug.log')) {
			unlink(LOGS . 'debug.log');
		}
	}

/**
 * Test that errors going into CakeLog include traces.
 *
 * @return void
 */
	public function testHandleErrorLoggingTrace() {
		Configure::write('debug', 0);
		Configure::write('Error.trace', true);
		if (file_exists(LOGS . 'error.log')) {
			unlink(LOGS . 'error.log');
		}

		set_error_handler('ErrorHandler::handleError');
		$this->_restoreError = true;

		$out .= '';

		$result = file(LOGS . 'error.log');
		$this->assertMatchesRegularExpression(
			'/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} Warning: Warning \(2\): Undefined variable\s+\$out in \[.+ line \d+\]$/',
			$result[0]
		);
		$this->assertMatchesRegularExpression('/^Trace:/', $result[1]);
		$this->assertMatchesRegularExpression('/^ErrorHandlerTest\:\:testHandleErrorLoggingTrace\(\)/', $result[3]);
		if (file_exists(LOGS . 'debug.log')) {
			unlink(LOGS . 'debug.log');
		}
	}

/**
 * test handleException generating a page.
 *
 * @return void
 */
	public function testHandleException() {
		$error = new NotFoundException('Kaboom!');
		ob_start();
		ErrorHandler::handleException($error);
		$result = ob_get_clean();
		$this->assertMatchesRegularExpression('/Kaboom!/', $result, 'message missing.');
	}

/**
 * test handleException generating log.
 *
 * @return void
 */
	public function testHandleExceptionLog() {
		if (file_exists(LOGS . 'error.log')) {
			unlink(LOGS . 'error.log');
		}
		Configure::write('Exception.log', true);
		$error = new NotFoundException('Kaboom!');

		ob_start();
		ErrorHandler::handleException($error);
		$result = ob_get_clean();
		$this->assertMatchesRegularExpression('/Kaboom!/', $result, 'message missing.');

		$log = file(LOGS . 'error.log');
		$this->assertStringContainsString('[NotFoundException] Kaboom!', $log[0], 'message missing.');
		$this->assertStringContainsString('ErrorHandlerTest->testHandleExceptionLog', $log[2], 'Stack trace missing.');
	}

/**
 * test handleException generating log.
 *
 * @return void
 */
	public function testHandleExceptionLogSkipping() {
		if (file_exists(LOGS . 'error.log')) {
			unlink(LOGS . 'error.log');
		}
		Configure::write('Exception.log', true);
		Configure::write('Exception.skipLog', array('NotFoundException'));
		$notFound = new NotFoundException('Kaboom!');
		$forbidden = new ForbiddenException('Fooled you!');

		ob_start();
		ErrorHandler::handleException($notFound);
		$result = ob_get_clean();
		$this->assertMatchesRegularExpression('/Kaboom!/', $result, 'message missing.');

		ob_start();
		ErrorHandler::handleException($forbidden);
		$result = ob_get_clean();
		$this->assertMatchesRegularExpression('/Fooled you!/', $result, 'message missing.');

		$log = file(LOGS . 'error.log');
		$this->assertStringNotContainsString('[NotFoundException] Kaboom!', $log[0], 'message should not be logged.');
		$this->assertStringContainsString('[ForbiddenException] Fooled you!', $log[0], 'message missing.');
	}

/**
 * tests it is possible to load a plugin exception renderer
 *
 * @return void
 */
	public function testLoadPluginHandler() {
		App::build(array(
			'Plugin' => array(
				CAKE . 'Test' . DS . 'test_app' . DS . 'Plugin' . DS
			)
		), App::RESET);
		CakePlugin::load('TestPlugin');
		Configure::write('Exception.renderer', 'TestPlugin.TestPluginExceptionRenderer');
		$error = new NotFoundException('Kaboom!');
		ob_start();
		ErrorHandler::handleException($error);
		$result = ob_get_clean();
		$this->assertEquals('Rendered by test plugin', $result);
		CakePlugin::unload();
	}

/**
 * test handleFatalError generating a page.
 *
 * These tests start two buffers as handleFatalError blows the outer one up.
 *
 * @return void
 */
	public function testHandleFatalErrorPage() {
		$line = __LINE__;

		ob_start();
		ob_start();
		Configure::write('debug', 1);
		ErrorHandler::handleFatalError(E_ERROR, 'Something wrong', __FILE__, $line);
		$result = ob_get_clean();
		$this->assertStringContainsString('Something wrong', $result, 'message missing.');
		$this->assertStringContainsString(__FILE__, $result, 'filename missing.');
		$this->assertStringContainsString((string)$line, $result, 'line missing.');

		ob_start();
		ob_start();
		Configure::write('debug', 0);
		ErrorHandler::handleFatalError(E_ERROR, 'Something wrong', __FILE__, $line);
		$result = ob_get_clean();
		$this->assertStringNotContainsString('Something wrong', $result, 'message must not appear.');
		$this->assertStringNotContainsString(__FILE__, $result, 'filename must not appear.');
		$this->assertStringContainsString('An Internal Error Has Occurred', $result);
	}

/**
 * test handleException generating log.
 *
 * @return void
 */
	public function testHandleFatalErrorLog() {
		if (file_exists(LOGS . 'error.log')) {
			unlink(LOGS . 'error.log');
		}

		ob_start();
		ErrorHandler::handleFatalError(E_ERROR, 'Something wrong', __FILE__, __LINE__);
		ob_clean();

		$log = file(LOGS . 'error.log');
		$this->assertStringContainsString(__FILE__, $log[0], 'missing filename');
		$this->assertStringContainsString('[FatalErrorException] Something wrong', $log[1], 'message missing.');
	}

/**
 * testExceptionRendererNestingDebug method
 *
 * @return void
 */
	public function testExceptionRendererNestingDebug() {
		Configure::write('debug', 2);
		Configure::write('Exception.renderer', 'FaultyExceptionRenderer');

		$result = false;
		try {
			ob_start();
			ob_start();
			ErrorHandler::handleFatalError(E_USER_ERROR, 'Initial error', __FILE__, __LINE__);
		} catch (Exception $e) {
			$result = $e instanceof FatalErrorException;
		}

		restore_error_handler();
		$this->assertTrue($result);
	}

/**
 * testExceptionRendererNestingProduction method
 *
 * @return void
 */
	public function testExceptionRendererNestingProduction() {
		Configure::write('debug', 0);
		Configure::write('Exception.renderer', 'FaultyExceptionRenderer');

		$result = false;
		try {
			ob_start();
			ob_start();
			ErrorHandler::handleFatalError(E_USER_ERROR, 'Initial error', __FILE__, __LINE__);
		} catch (Exception $e) {
			$result = $e instanceof InternalErrorException;
		}

		restore_error_handler();
		$this->assertTrue($result);
	}

}
