<?php
/**
 * PagesControllerTest file
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
 * @package       Cake.Test.Case.Controller
 * @since         CakePHP(tm) v 1.2.0.5436
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('PagesController', 'Controller');
App::uses('CakeRequest', 'Network');

/**
 * PagesControllerTest class
 *
 * @package       Cake.Test.Case.Controller
 */
class PagesControllerTest extends CakeTestCase {

/**
 * testDisplay method
 *
 * @return void
 */
	public function testDisplay() {
		App::build(array(
			'View' => array(
				CAKE . 'Test' . DS . 'test_app' . DS . 'View' . DS
			)
		));
		$Pages = new PagesController(new CakeRequest(null, false), new CakeResponse());

		$Pages->viewPath = 'Posts';
		$Pages->display('index');
		$this->assertMatchesRegularExpression('/posts index/', $Pages->response->body());
		$this->assertEquals('index', $Pages->viewVars['page']);

		$Pages->viewPath = 'Themed';
		$Pages->display('TestTheme', 'Posts', 'index');
		$this->assertMatchesRegularExpression('/posts index themed view/', $Pages->response->body());
		$this->assertEquals('TestTheme', $Pages->viewVars['page']);
		$this->assertEquals('Posts', $Pages->viewVars['subpage']);
	}

/**
 * Test that missing view renders 404 page in production
 *
 * @return void
 */
	public function testMissingView() {
		$this->expectException(NotFoundException::class);
		$this->expectExceptionCode(404);
		Configure::write('debug', 0);
		$Pages = new PagesController(new CakeRequest(null, false), new CakeResponse());
		$Pages->display('non_existing_page');
	}

/**
 * Test that missing view in debug mode renders missing_view error page
 *
 * @return void
 */
	public function testMissingViewInDebug() {
		$this->expectException(MissingViewException::class);
		$this->expectExceptionCode(500);
		Configure::write('debug', 1);
		$Pages = new PagesController(new CakeRequest(null, false), new CakeResponse());
		$Pages->display('non_existing_page');
	}

/**
 * Test directory traversal protection
 *
 * @return void
 */
	public function testDirectoryTraversalProtection() {
		$this->expectException(ForbiddenException::class);
		$this->expectExceptionCode(403);
		App::build(array(
			'View' => array(
				CAKE . 'Test' . DS . 'test_app' . DS . 'View' . DS
			)
		));
		$Pages = new PagesController(new CakeRequest(null, false), new CakeResponse());
		$Pages->display('..', 'Posts', 'index');
	}
}
