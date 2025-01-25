<?php
/**
 * Short description for file.
 *
 * PHP 5
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
 * @package       Cake.Test.Fixture
 * @since         CakePHP(tm) v 2.5.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Short description for class.
 *
 * @package       Cake.Test.Fixture
 */
class UnsignedFixture extends CakeTestFixture {

/**
 * table property
 *
 * @var array
 */
	public $table = 'unsigned';

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'uinteger' => ['type' => 'integer', 'null' => '', 'default' => '1', 'length' => '8', 'key' => 'primary', 'unsigned' => true],
		'integer' => ['type' => 'integer', 'length' => '8', 'unsigned' => false],
		'usmallinteger' => ['type' => 'smallinteger', 'unsigned' => true],
		'smallinteger' => ['type' => 'smallinteger', 'unsigned' => false],
		'utinyinteger' => ['type' => 'tinyinteger', 'unsigned' => true],
		'tinyinteger' => ['type' => 'tinyinteger', 'unsigned' => false],
		'udecimal' => ['type' => 'decimal', 'length' => '4', 'unsigned' => true],
		'decimal' => ['type' => 'decimal', 'length' => '4'],
		'biginteger' => ['type' => 'biginteger', 'length' => '20', 'default' => 3],
		'ubiginteger' => ['type' => 'biginteger', 'length' => '20', 'default' => 3, 'unsigned' => true],
		'float' => ['type' => 'float', 'length' => '4'],
		'ufloat' => ['type' => 'float', 'length' => '4', 'unsigned' => true],
		'string' => ['type' => 'string', 'length' => '4'],
		'tableParameters' => [
			'engine' => 'MyISAM'
		]
	];

/**
 * records property
 *
 * @var array
 */
	public $records = [];
}
