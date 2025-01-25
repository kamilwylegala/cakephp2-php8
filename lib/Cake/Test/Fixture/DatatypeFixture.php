<?php
/**
 * Short description for file.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       Cake.Test.Fixture
 * @since         CakePHP(tm) v 1.2.0.7026
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Short description for class.
 *
 * @package       Cake.Test.Fixture
 */
class DatatypeFixture extends CakeTestFixture {

/**
 * Fields property
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => 0, 'key' => 'primary'],
		'float_field' => ['type' => 'float', 'length' => '5,2', 'null' => false, 'default' => null],
		'decimal_field' => ['type' => 'decimal', 'length' => '6,3', 'default' => '0.000'],
		'huge_int' => ['type' => 'biginteger'],
		'normal_int' => ['type' => 'integer'],
		'small_int' => ['type' => 'smallinteger'],
		'tiny_int' => ['type' => 'tinyinteger'],
		'bool' => ['type' => 'boolean', 'null' => false, 'default' => false],
	];

/**
 * Records property
 *
 * @var array
 */
	public $records = [
		[
			'id' => 1,
			'float_field' => 42.23,
			'huge_int' => '9223372036854775807',
			'normal_int' => 2147483647,
			'small_int' => 32767,
			'tiny_int' => 127,
			'bool' => 0
		],
	];
}
