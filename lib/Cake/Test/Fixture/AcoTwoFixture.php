<?php
/**
 * Short description for file.
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
 * @since         CakePHP(tm) v 1.2.0.4667
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Short description for class.
 *
 * @package       Cake.Test.Fixture
 */
class AcoTwoFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'id'		=> ['type' => 'integer', 'key' => 'primary'],
		'parent_id'	=> ['type' => 'integer', 'length' => 10, 'null' => true],
		'model'		=> ['type' => 'string', 'null' => true],
		'foreign_key' => ['type' => 'integer', 'length' => 10, 'null' => true],
		'alias'		=> ['type' => 'string', 'default' => ''],
		'lft'		=> ['type' => 'integer', 'length' => 10, 'null' => true],
		'rght'		=> ['type' => 'integer', 'length' => 10, 'null' => true]
	];

/**
 * records property
 *
 * @var array
 */
	public $records = [
		['parent_id' => null, 'model' => null, 'foreign_key' => null, 'alias' => 'ROOT', 'lft' => 1, 'rght' => 20],
		['parent_id' => 1, 'model' => null, 'foreign_key' => null, 'alias' => 'tpsReports', 'lft' => 2, 'rght' => 9],
		['parent_id' => 2, 'model' => null, 'foreign_key' => null, 'alias' => 'view', 'lft' => 3, 'rght' => 6],
		['parent_id' => 3, 'model' => null, 'foreign_key' => null, 'alias' => 'current', 'lft' => 4, 'rght' => 5],
		['parent_id' => 2, 'model' => null, 'foreign_key' => null, 'alias' => 'update', 'lft' => 7, 'rght' => 8],
		['parent_id' => 1, 'model' => null, 'foreign_key' => null, 'alias' => 'printers', 'lft' => 10, 'rght' => 19],
		['parent_id' => 6, 'model' => null, 'foreign_key' => null, 'alias' => 'print', 'lft' => 11, 'rght' => 14],
		['parent_id' => 7, 'model' => null, 'foreign_key' => null, 'alias' => 'lettersize', 'lft' => 12, 'rght' => 13],
		['parent_id' => 6, 'model' => null, 'foreign_key' => null, 'alias' => 'refill', 'lft' => 15, 'rght' => 16],
		['parent_id' => 6, 'model' => null, 'foreign_key' => null, 'alias' => 'smash', 'lft' => 17, 'rght' => 18],
	];
}
