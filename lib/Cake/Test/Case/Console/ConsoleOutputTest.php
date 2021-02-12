<?php
/**
 * ConsoleOutputTest file
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
 * @package       Cake.Test.Case.Console
 * @since         CakePHP(tm) v 1.2.0.5432
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use PHPUnit\Framework\MockObject\MockObject;

App::uses('ConsoleOutput', 'Console');

/**
 * ConsoleOutputTest
 *
 * @package       Cake.Test.Case.Console
 */
class ConsoleOutputTest extends CakeTestCase {
	/**
	 * @var ConsoleOutput|MockObject
	 */
	private $consoleOutput;

	/**
 * setup
 *
 * @return void
 */
	public function setUp(): void {
		parent::setUp();
		$this->consoleOutput = $this->getMock('ConsoleOutput', array('_write'));
		$this->consoleOutput->outputAs(ConsoleOutput::COLOR);
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown(): void {
		parent::tearDown();
		unset($this->consoleOutput);
	}

/**
	 * test writing with no new line
	 *
	 * @return void
	 */
	public function testWriteNoNewLine() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with('Some output');

		$this->consoleOutput->write('Some output', false);
	}

/**
	 * test writing with no new line
	 *
	 * @return void
	 */
	public function testWriteNewLine() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with('Some output' . PHP_EOL);

		$this->consoleOutput->write('Some output');
	}

/**
	 * test write() with multiple new lines
	 *
	 * @return void
	 */
	public function testWriteMultipleNewLines() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with('Some output' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL);

		$this->consoleOutput->write('Some output', 4);
	}

/**
	 * test writing an array of messages.
	 *
	 * @return void
	 */
	public function testWriteArray() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with('Line' . PHP_EOL . 'Line' . PHP_EOL . 'Line' . PHP_EOL);

		$this->consoleOutput->write(array('Line', 'Line', 'Line'));
	}

/**
	 * test writing an array of messages.
	 *
	 * @return void
	 */
	public function testOverwrite() {
		$testString = "Text";

		$this->consoleOutput->expects($this->at(0))->method('_write')
			->with($testString);

		$this->consoleOutput->expects($this->at(1))->method('_write')
			->with("");

		$this->consoleOutput->expects($this->at(2))->method('_write')
			->with("Overwriting text");

		$this->consoleOutput->write($testString, 0);
		$this->consoleOutput->overwrite("Overwriting text");
	}

/**
 * test getting a style.
 *
 * @return void
 */
	public function testStylesGet() {
		$result = $this->consoleOutput->styles('error');
		$expected = array('text' => 'red', 'underline' => true);
		$this->assertEquals($expected, $result);

		$this->assertNull($this->consoleOutput->styles('made_up_goop'));

		$result = $this->consoleOutput->styles();
		$this->assertNotEmpty($result, 'error', 'Error is missing');
		$this->assertNotEmpty($result, 'warning', 'Warning is missing');
	}

/**
 * test adding a style.
 *
 * @return void
 */
	public function testStylesAdding() {
		$this->consoleOutput->styles('test', array('text' => 'red', 'background' => 'black'));
		$result = $this->consoleOutput->styles('test');
		$expected = array('text' => 'red', 'background' => 'black');
		$this->assertEquals($expected, $result);

		$this->assertTrue($this->consoleOutput->styles('test', false), 'Removing a style should return true.');
		$this->assertNull($this->consoleOutput->styles('test'), 'Removed styles should be null.');
	}

/**
	 * test formatting text with styles.
	 *
	 * @return void
	 */
	public function testFormattingSimple() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with("\033[31;4mError:\033[0m Something bad");

		$this->consoleOutput->write('<error>Error:</error> Something bad', false);
	}

/**
	 * test that formatting doesn't eat tags it doesn't know about.
	 *
	 * @return void
	 */
	public function testFormattingNotEatingTags() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with("<red> Something bad");

		$this->consoleOutput->write('<red> Something bad', false);
	}

/**
	 * test formatting with custom styles.
	 *
	 * @return void
	 */
	public function testFormattingCustom() {
		$this->consoleOutput->styles('annoying', array(
			'text' => 'magenta',
			'background' => 'cyan',
			'blink' => true,
			'underline' => true
		));

		$this->consoleOutput->expects($this->once())->method('_write')
			->with("\033[35;46;5;4mAnnoy:\033[0m Something bad");

		$this->consoleOutput->write('<annoying>Annoy:</annoying> Something bad', false);
	}

/**
	 * test formatting text with missing styles.
	 *
	 * @return void
	 */
	public function testFormattingMissingStyleName() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with("<not_there>Error:</not_there> Something bad");

		$this->consoleOutput->write('<not_there>Error:</not_there> Something bad', false);
	}

/**
	 * test formatting text with multiple styles.
	 *
	 * @return void
	 */
	public function testFormattingMultipleStylesName() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with("\033[31;4mBad\033[0m \033[33mWarning\033[0m Regular");

		$this->consoleOutput->write('<error>Bad</error> <warning>Warning</warning> Regular', false);
	}

/**
	 * test that multiple tags of the same name work in one string.
	 *
	 * @return void
	 */
	public function testFormattingMultipleSameTags() {
		$this->consoleOutput->expects($this->once())->method('_write')
			->with("\033[31;4mBad\033[0m \033[31;4mWarning\033[0m Regular");

		$this->consoleOutput->write('<error>Bad</error> <error>Warning</error> Regular', false);
	}

/**
	 * test raw output not getting tags replaced.
	 *
	 * @return void
	 */
	public function testOutputAsRaw() {
		$this->consoleOutput->outputAs(ConsoleOutput::RAW);
		$this->consoleOutput->expects($this->once())->method('_write')
			->with('<error>Bad</error> Regular');

		$this->consoleOutput->write('<error>Bad</error> Regular', false);
	}

/**
	 * test plain output.
	 *
	 * @return void
	 */
	public function testOutputAsPlain() {
		$this->consoleOutput->outputAs(ConsoleOutput::PLAIN);
		$this->consoleOutput->expects($this->once())->method('_write')
			->with('Bad Regular');

		$this->consoleOutput->write('<error>Bad</error> Regular', false);
	}

/**
 * test plain output when php://output, as php://output is
 * not compatible with posix_ functions.
 *
 * @return void
 */
	public function testOutputAsPlainWhenOutputStream() {
		$output = $this->getMock('ConsoleOutput', array('_write'), array('php://output'));
		$this->assertEquals(ConsoleOutput::PLAIN, $output->outputAs());
	}

/**
	 * test plain output only strips tags used for formatting.
	 *
	 * @return void
	 */
	public function testOutputAsPlainSelectiveTagRemoval() {
		$this->consoleOutput->outputAs(ConsoleOutput::PLAIN);
		$this->consoleOutput->expects($this->once())->method('_write')
			->with('Bad Regular <b>Left</b> <i>behind</i> <name>');

		$this->consoleOutput->write('<error>Bad</error> Regular <b>Left</b> <i>behind</i> <name>', false);
	}
}
