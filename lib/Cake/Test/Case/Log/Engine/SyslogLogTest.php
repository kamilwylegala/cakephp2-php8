<?php
/**
 * CakePHP(tm) Tests <https://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Case.Log.Engine
 * @since         CakePHP(tm) v 2.4
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('SyslogLog', 'Log/Engine');

/**
 * SyslogLogTest class
 *
 * @package       Cake.Test.Case.Log.Engine
 */
class SyslogLogTest extends CakeTestCase {

/**
 * Tests that the connection to the logger is open with the right arguments
 *
 * @return void
 */
	public function testOpenLog() {
		$log = $this->getMock('SyslogLog', ['_open', '_write']);
		$log->expects($this->once())->method('_open')->with('', LOG_ODELAY, LOG_USER);
		$log->write('debug', 'message');

		$log = $this->getMock('SyslogLog', ['_open', '_write']);
		$log->config([
			'prefix' => 'thing',
			'flag' => LOG_NDELAY,
			'facility' => LOG_MAIL,
			'format' => '%s: %s'
		]);
		$log->expects($this->once())->method('_open')
			->with('thing', LOG_NDELAY, LOG_MAIL);
		$log->write('debug', 'message');
	}

/**
 * Tests that single lines are written to syslog
 *
 * @dataProvider typesProvider
 * @return void
 */
	public function testWriteOneLine($type, $expected) {
		$log = $this->getMock('SyslogLog', ['_open', '_write']);
		$log->expects($this->once())->method('_write')->with($expected, $type . ': Foo');
		$log->write($type, 'Foo');
	}

/**
 * Tests that multiple lines are split and logged separately
 *
 * @return void
 */
	public function testWriteMultiLine() {
		$log = $this->getMock('SyslogLog', ['_open', '_write']);
		$log->expects($this->at(1))->method('_write')->with(LOG_DEBUG, 'debug: Foo');
		$log->expects($this->at(2))->method('_write')->with(LOG_DEBUG, 'debug: Bar');
		$log->expects($this->exactly(2))->method('_write');
		$log->write('debug', "Foo\nBar");
	}

/**
 * Data provider for the write function test
 *
 * @return array
 */
	public function typesProvider() {
		return [
			['emergency', LOG_EMERG],
			['alert', LOG_ALERT],
			['critical', LOG_CRIT],
			['error', LOG_ERR],
			['warning', LOG_WARNING],
			['notice', LOG_NOTICE],
			['info', LOG_INFO],
			['debug', LOG_DEBUG]
		];
	}

}

