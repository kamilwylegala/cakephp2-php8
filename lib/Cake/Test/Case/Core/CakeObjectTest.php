<?php
/**
 * ObjectTest file
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
 * @package       Cake.Test.Case.Core
 * @since         CakePHP(tm) v 1.2.0.5432
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('CakeObject', 'Core');
App::uses('Object', 'Core');
App::uses('Router', 'Routing');
App::uses('Controller', 'Controller');
App::uses('Model', 'Model');

/**
 * RequestActionPost class
 *
 * @package       Cake.Test.Case.Core
 */
class RequestActionPost extends CakeTestModel {

/**
 * useTable property
 *
 * @var string
 */
	public $useTable = 'posts';
}

/**
 * RequestActionController class
 *
 * @package       Cake.Test.Case.Core
 */
class RequestActionController extends Controller {

/**
 * uses property
 *
 * @var array
 */
	public $uses = ['RequestActionPost'];

/**
 * test_request_action method
 *
 * @return void
 */
	public function test_request_action() {
		return 'This is a test';
	}

/**
 * another_ra_test method
 *
 * @param mixed $id
 * @param mixed $other
 * @return void
 */
	public function another_ra_test($id, $other) {
		return $id + $other;
	}

/**
 * normal_request_action method
 *
 * @return string Hello World!
 */
	public function normal_request_action() {
		return 'Hello World';
	}

/**
 * returns $this->here
 *
 * @return string $this->here.
 */
	public function return_here() {
		return $this->request->here();
	}

/**
 * paginate_request_action method
 *
 * @return true
 */
	public function paginate_request_action() {
		$this->paginate();
		return true;
	}

/**
 * post pass, testing post passing
 *
 * @return array
 */
	public function post_pass() {
		return $this->request->data;
	}

/**
 * test param passing and parsing.
 *
 * @return array
 */
	public function params_pass() {
		return $this->request;
	}

	public function param_check() {
		$this->autoRender = false;
		$content = '';
		if (isset($this->request->params[0])) {
			$content = 'return found';
		}
		$this->response->body($content);
	}

}

/**
 * TestCakeObject class
 *
 * @package       Cake.Test.Case.Core
 */
class TestCakeObject extends CakeObject {

/**
 * firstName property
 *
 * @var string
 */
	public $firstName = 'Joel';

/**
 * lastName property
 *
 * @var string
 */
	public $lastName = 'Moss';

/**
 * methodCalls property
 *
 * @var array
 */
	public $methodCalls = [];

/**
 * emptyMethod method
 *
 * @return void
 */
	public function emptyMethod() {
		$this->methodCalls[] = 'emptyMethod';
	}

/**
 * oneParamMethod method
 *
 * @param mixed $param
 * @return void
 */
	public function oneParamMethod($param) {
		$this->methodCalls[] = ['oneParamMethod' => [$param]];
	}

/**
 * twoParamMethod method
 *
 * @param mixed $param
 * @param mixed $paramTwo
 * @return void
 */
	public function twoParamMethod($param, $paramTwo) {
		$this->methodCalls[] = ['twoParamMethod' => [$param, $paramTwo]];
	}

/**
 * threeParamMethod method
 *
 * @param mixed $param
 * @param mixed $paramTwo
 * @param mixed $paramThree
 * @return void
 */
	public function threeParamMethod($param, $paramTwo, $paramThree) {
		$this->methodCalls[] = ['threeParamMethod' => [$param, $paramTwo, $paramThree]];
	}

/**
 * fourParamMethod method
 *
 * @param mixed $param
 * @param mixed $paramTwo
 * @param mixed $paramThree
 * @param mixed $paramFour
 * @return void
 */
	public function fourParamMethod($param, $paramTwo, $paramThree, $paramFour) {
		$this->methodCalls[] = ['fourParamMethod' => [$param, $paramTwo, $paramThree, $paramFour]];
	}

/**
 * fiveParamMethod method
 *
 * @param mixed $param
 * @param mixed $paramTwo
 * @param mixed $paramThree
 * @param mixed $paramFour
 * @param mixed $paramFive
 * @return void
 */
	public function fiveParamMethod($param, $paramTwo, $paramThree, $paramFour, $paramFive) {
		$this->methodCalls[] = ['fiveParamMethod' => [$param, $paramTwo, $paramThree, $paramFour, $paramFive]];
	}

/**
 * crazyMethod method
 *
 * @param mixed $param
 * @param mixed $paramTwo
 * @param mixed $paramThree
 * @param mixed $paramFour
 * @param mixed $paramFive
 * @param mixed $paramSix
 * @param mixed $paramSeven
 * @return void
 */
	public function crazyMethod($param, $paramTwo, $paramThree, $paramFour, $paramFive, $paramSix, $paramSeven = null) {
		$this->methodCalls[] = ['crazyMethod' => [$param, $paramTwo, $paramThree, $paramFour, $paramFive, $paramSix, $paramSeven]];
	}

/**
 * methodWithOptionalParam method
 *
 * @param mixed $param
 * @return void
 */
	public function methodWithOptionalParam($param = null) {
		$this->methodCalls[] = ['methodWithOptionalParam' => [$param]];
	}

/**
 * Set properties.
 *
 * @param array $properties The $properties.
 * @return void
 */
	public function set($properties = []) {
		return parent::_set($properties);
	}

}

/**
 * ObjectTestModel class
 *
 * @package       Cake.Test.Case.Core
 */
class ObjectTestModel extends CakeTestModel {

	public $useTable = false;

}

/**
 * CakeObject Test class
 *
 * @package       Cake.Test.Case.Core
 */
class ObjectTest extends CakeTestCase {

/**
 * fixtures
 *
 * @var string
 */
	public $fixtures = ['core.post', 'core.test_plugin_comment', 'core.comment'];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() : void {
		parent::setUp();
		$this->object = new TestCakeObject();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() : void {
		parent::tearDown();
		CakePlugin::unload();
		unset($this->object);
	}

/**
 * testLog method
 *
 * @return void
 */
	public function testLog() {
		if (file_exists(LOGS . 'error.log')) {
			unlink(LOGS . 'error.log');
		}
		$this->assertTrue($this->object->log('Test warning 1'));
		$this->assertTrue($this->object->log(['Test' => 'warning 2']));
		$result = file(LOGS . 'error.log');
		$this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Error: Test warning 1$/', $result[0]);
		$this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Error: Array$/', $result[1]);
		$this->assertMatchesRegularExpression('/^\($/', $result[2]);
		$this->assertMatchesRegularExpression('/\[Test\] => warning 2$/', $result[3]);
		$this->assertMatchesRegularExpression('/^\)$/', $result[4]);
		unlink(LOGS . 'error.log');

		$this->assertTrue($this->object->log('Test warning 1', LOG_WARNING));
		$this->assertTrue($this->object->log(['Test' => 'warning 2'], LOG_WARNING));
		$result = file(LOGS . 'error.log');
		$this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Test warning 1$/', $result[0]);
		$this->assertMatchesRegularExpression('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: Array$/', $result[1]);
		$this->assertMatchesRegularExpression('/^\($/', $result[2]);
		$this->assertMatchesRegularExpression('/\[Test\] => warning 2$/', $result[3]);
		$this->assertMatchesRegularExpression('/^\)$/', $result[4]);
		unlink(LOGS . 'error.log');
	}

/**
 * testSet method
 *
 * @return void
 */
	public function testSet() {
		$this->object->set('a string');
		$this->assertEquals('Joel', $this->object->firstName);

		$this->object->set(['firstName']);
		$this->assertEquals('Joel', $this->object->firstName);

		$this->object->set(['firstName' => 'Ashley']);
		$this->assertEquals('Ashley', $this->object->firstName);

		$this->object->set(['firstName' => 'Joel', 'lastName' => 'Moose']);
		$this->assertEquals('Joel', $this->object->firstName);
		$this->assertEquals('Moose', $this->object->lastName);
	}

/**
 * testToString method
 *
 * @return void
 */
	public function testToString() {
		$result = strtolower($this->object->toString());
		$this->assertEquals('testcakeobject', $result);
	}

/**
 * testMethodDispatching method
 *
 * @return void
 */
	public function testMethodDispatching() {
		$this->object->emptyMethod();
		$expected = ['emptyMethod'];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->oneParamMethod('Hello');
		$expected[] = ['oneParamMethod' => ['Hello']];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->twoParamMethod(true, false);
		$expected[] = ['twoParamMethod' => [true, false]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->threeParamMethod(true, false, null);
		$expected[] = ['threeParamMethod' => [true, false, null]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->crazyMethod(1, 2, 3, 4, 5, 6, 7);
		$expected[] = ['crazyMethod' => [1, 2, 3, 4, 5, 6, 7]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object = new TestCakeObject();
		$this->assertSame($this->object->methodCalls, []);

		$this->object->dispatchMethod('emptyMethod');
		$expected = ['emptyMethod'];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('oneParamMethod', ['Hello']);
		$expected[] = ['oneParamMethod' => ['Hello']];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('twoParamMethod', [true, false]);
		$expected[] = ['twoParamMethod' => [true, false]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('threeParamMethod', [true, false, null]);
		$expected[] = ['threeParamMethod' => [true, false, null]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('fourParamMethod', [1, 2, 3, 4]);
		$expected[] = ['fourParamMethod' => [1, 2, 3, 4]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('fiveParamMethod', [1, 2, 3, 4, 5]);
		$expected[] = ['fiveParamMethod' => [1, 2, 3, 4, 5]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('crazyMethod', [1, 2, 3, 4, 5, 6, 7]);
		$expected[] = ['crazyMethod' => [1, 2, 3, 4, 5, 6, 7]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('methodWithOptionalParam', ['Hello']);
		$expected[] = ['methodWithOptionalParam' => ["Hello"]];
		$this->assertSame($expected, $this->object->methodCalls);

		$this->object->dispatchMethod('methodWithOptionalParam');
		$expected[] = ['methodWithOptionalParam' => [null]];
		$this->assertSame($expected, $this->object->methodCalls);
	}

/**
 * testRequestAction method
 *
 * @return void
 */
	public function testRequestAction() {
		App::build([
			'Model' => [CAKE . 'Test' . DS . 'test_app' . DS . 'Model' . DS],
			'View' => [CAKE . 'Test' . DS . 'test_app' . DS . 'View' . DS],
			'Controller' => [CAKE . 'Test' . DS . 'test_app' . DS . 'Controller' . DS]
		], App::RESET);
		$this->assertNull(Router::getRequest(), 'request stack should be empty.');

		$result = $this->object->requestAction('');
		$this->assertFalse($result);

		$result = $this->object->requestAction('/request_action/test_request_action');
		$expected = 'This is a test';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(
			Configure::read('App.fullBaseUrl') . '/request_action/test_request_action'
		);
		$expected = 'This is a test';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction('/request_action/another_ra_test/2/5');
		$expected = 7;
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction('/tests_apps/index', ['return']);
		$expected = 'This is the TestsAppsController index view ';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction('/tests_apps/some_method');
		$expected = 5;
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction('/request_action/paginate_request_action');
		$this->assertTrue($result);

		$result = $this->object->requestAction('/request_action/normal_request_action');
		$expected = 'Hello World';
		$this->assertEquals($expected, $result);

		$this->assertNull(Router::getRequest(), 'requests were not popped off the stack, this will break url generation');
	}

/**
 * Test that here() is calculated correctly in requestAction
 *
 * @return void
 */
	public function testRequestActionHere() {
		$url = '/request_action/return_here?key=value';
		$result = $this->object->requestAction($url);
		$this->assertStringEndsWith($url, $result);
	}

/**
 * test requestAction() and plugins.
 *
 * @return void
 */
	public function testRequestActionPlugins() {
		App::build([
			'Plugin' => [CAKE . 'Test' . DS . 'test_app' . DS . 'Plugin' . DS],
		], App::RESET);
		CakePlugin::load('TestPlugin');
		Router::reload();

		$result = $this->object->requestAction('/test_plugin/tests/index', ['return']);
		$expected = 'test plugin index';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction('/test_plugin/tests/index/some_param', ['return']);
		$expected = 'test plugin index';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(
			['controller' => 'tests', 'action' => 'index', 'plugin' => 'test_plugin'], ['return']
		);
		$expected = 'test plugin index';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction('/test_plugin/tests/some_method');
		$expected = 25;
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(
			['controller' => 'tests', 'action' => 'some_method', 'plugin' => 'test_plugin']
		);
		$expected = 25;
		$this->assertEquals($expected, $result);
	}

/**
 * test requestAction() with arrays.
 *
 * @return void
 */
	public function testRequestActionArray() {
		App::build([
			'Model' => [CAKE . 'Test' . DS . 'test_app' . DS . 'Model' . DS],
			'View' => [CAKE . 'Test' . DS . 'test_app' . DS . 'View' . DS],
			'Controller' => [CAKE . 'Test' . DS . 'test_app' . DS . 'Controller' . DS],
			'Plugin' => [CAKE . 'Test' . DS . 'test_app' . DS . 'Plugin' . DS]
		], App::RESET);
		CakePlugin::load(['TestPlugin']);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'test_request_action']
		);
		$expected = 'This is a test';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'another_ra_test'],
			['pass' => ['5', '7']]
		);
		$expected = 12;
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(
			['controller' => 'tests_apps', 'action' => 'index'], ['return']
		);
		$expected = 'This is the TestsAppsController index view ';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(['controller' => 'tests_apps', 'action' => 'some_method']);
		$expected = 5;
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'normal_request_action']
		);
		$expected = 'Hello World';
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'paginate_request_action']
		);
		$this->assertTrue($result);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'paginate_request_action'],
			['pass' => [5], 'named' => ['param' => 'value']]
		);
		$this->assertTrue($result);
	}

/**
 * Test that requestAction() does not forward the 0 => return value.
 *
 * @return void
 */
	public function testRequestActionRemoveReturnParam() {
		$result = $this->object->requestAction(
			'/request_action/param_check', ['return']
		);
		$this->assertEquals('', $result, 'Return key was found');
	}

/**
 * Test that requestAction() is populating $this->params properly
 *
 * @return void
 */
	public function testRequestActionParamParseAndPass() {
		$result = $this->object->requestAction('/request_action/params_pass');
		$this->assertEquals('request_action/params_pass', $result->url);
		$this->assertEquals('request_action', $result['controller']);
		$this->assertEquals('params_pass', $result['action']);
		$this->assertEquals(null, $result['plugin']);

		$result = $this->object->requestAction('/request_action/params_pass/sort:desc/limit:5');
		$expected = ['sort' => 'desc', 'limit' => 5];
		$this->assertEquals($expected, $result['named']);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'params_pass'],
			['named' => ['sort' => 'desc', 'limit' => 5]]
		);
		$this->assertEquals($expected, $result['named']);
	}

/**
 * Test that requestAction handles get parameters correctly.
 *
 * @return void
 */
	public function testRequestActionGetParameters() {
		$result = $this->object->requestAction(
			'/request_action/params_pass?get=value&limit=5'
		);
		$this->assertEquals('value', $result->query['get']);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'params_pass'],
			['url' => ['get' => 'value', 'limit' => 5]]
		);
		$this->assertEquals('value', $result->query['get']);
	}

/**
 * test that requestAction does not fish data out of the POST
 * superglobal.
 *
 * @return void
 */
	public function testRequestActionNoPostPassing() {
		$_tmp = $_POST;

		$_POST = ['data' => [
			'item' => 'value'
		]];
		$result = $this->object->requestAction(['controller' => 'request_action', 'action' => 'post_pass']);
		$this->assertEmpty($result);

		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'post_pass'],
			['data' => $_POST['data']]
		);
		$expected = $_POST['data'];
		$this->assertEquals($expected, $result);

		$result = $this->object->requestAction('/request_action/post_pass');
		$expected = $_POST['data'];
		$this->assertEquals($expected, $result);

		$_POST = $_tmp;
	}

/**
 * Test requestAction with post data.
 *
 * @return void
 */
	public function testRequestActionPostWithData() {
		$data = [
			'Post' => ['id' => 2]
		];
		$result = $this->object->requestAction(
			['controller' => 'request_action', 'action' => 'post_pass'],
			['data' => $data]
		);
		$this->assertEquals($data, $result);

		$result = $this->object->requestAction(
			'/request_action/post_pass',
			['data' => $data]
		);
		$this->assertEquals($data, $result);
	}

/**
 * Test backward compatibility
 *
 * @return voind
 */
	public function testBackwardCompatibility() {
		$this->skipIf(version_compare(PHP_VERSION, '7.0.0', '>='));

		$this->assertInstanceOf('Object', new ObjectTestModel);
	}
}
