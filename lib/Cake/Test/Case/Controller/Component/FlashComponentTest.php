<?php
/**
 * FlashComponentTest file
 *
 * Series of tests for flash component.
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
 * @package       Cake.Test.Case.Controller.Component
 * @since         CakePHP(tm) v 2.7.0-dev
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('FlashComponent', 'Controller/Component');
App::uses('ComponentCollection', 'Controller');

/**
 * FlashComponentTest class
 *
 * @package		Cake.Test.Case.Controller.Component
 */
class FlashComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() : void {
		parent::setUp();
		$this->Components = new ComponentCollection();
		$this->Flash = new FlashComponent($this->Components);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() : void {
		parent::tearDown();
		CakeSession::destroy();
	}

/**
 * testSet method
 *
 * @return void
 */
	public function testSet() {
		$this->assertNull(CakeSession::read('Message.flash'));

		$this->Flash->set('This is a test message');
		$expected = [
			[
				'message' => 'This is a test message',
				'key' => 'flash',
				'element' => 'Flash/default',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');

		$this->Flash->set('This is the first message');
		$this->Flash->set('This is the second message');
		$expected = [
			[
				'message' => 'This is the first message',
				'key' => 'flash',
				'element' => 'Flash/default',
				'params' => []
			],
			[
				'message' => 'This is the second message',
				'key' => 'flash',
				'element' => 'Flash/default',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');

		$this->Flash->set('This is a test message', [
			'element' => 'test',
			'params' => ['foo' => 'bar']
		]);
		$expected = [
			[
				'message' => 'This is a test message',
				'key' => 'flash',
				'element' => 'Flash/test',
				'params' => ['foo' => 'bar']
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');

		$this->Flash->set('This is a test message', ['element' => 'MyPlugin.alert']);
		$expected = [
			[
				'message' => 'This is a test message',
				'key' => 'flash',
				'element' => 'MyPlugin.Flash/alert',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');

		$this->Flash->set('This is a test message', ['key' => 'foobar']);
		$expected = [
			[
				'message' => 'This is a test message',
				'key' => 'foobar',
				'element' => 'Flash/default',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.foobar');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.foobar');

		$this->Flash->set('This is the first message');
		$this->Flash->set('This is the second message', ['clear' => true]);
		$expected = [
			[
				'message' => 'This is the second message',
				'key' => 'flash',
				'element' => 'Flash/default',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');
	}

/**
 * testSetWithException method
 *
 * @return void
 */
	public function testSetWithException() {
		$this->assertNull(CakeSession::read('Message.flash'));

		$this->Flash->set(new Exception('This is a test message', 404));
		$expected = [
			[
				'message' => 'This is a test message',
				'key' => 'flash',
				'element' => 'Flash/default',
				'params' => ['code' => 404]
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');
	}

/**
 * testSetWithComponentConfiguration method
 *
 * @return void
 */
	public function testSetWithComponentConfiguration() {
		$this->assertNull(CakeSession::read('Message.flash'));

		$FlashWithSettings = $this->Components->load('Flash', ['element' => 'test']);
		$FlashWithSettings->set('This is a test message');
		$expected = [
			[
				'message' => 'This is a test message',
				'key' => 'flash',
				'element' => 'Flash/test',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');
	}

/**
 * Test magic call method.
 *
 * @return void
 */
	public function testCall() {
		$this->assertNull(CakeSession::read('Message.flash'));

		$this->Flash->success('It worked');
		$expected = [
			[
				'message' => 'It worked',
				'key' => 'flash',
				'element' => 'Flash/success',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');

		$this->Flash->alert('It worked', ['plugin' => 'MyPlugin']);
		$expected = [
			[
				'message' => 'It worked',
				'key' => 'flash',
				'element' => 'MyPlugin.Flash/alert',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result);
		CakeSession::delete('Message.flash');

		$this->Flash->error('It did not work', ['element' => 'error_thing']);
		$expected = [
			[
				'message' => 'It did not work',
				'key' => 'flash',
				'element' => 'Flash/error',
				'params' => []
			]
		];
		$result = CakeSession::read('Message.flash');
		$this->assertEquals($expected, $result, 'Element is ignored in magic call.');
		CakeSession::delete('Message.flash');
	}
}
