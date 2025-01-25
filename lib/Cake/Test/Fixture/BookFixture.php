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
 * @since         CakePHP(tm) v 1.2.0.7198
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Short description for class.
 *
 * @package       Cake.Test.Fixture
 */
class BookFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer', 'key' => 'primary'],
		'isbn' => ['type' => 'string', 'length' => 13],
		'title' => ['type' => 'string', 'length' => 255],
		'author' => ['type' => 'string', 'length' => 255],
		'year' => ['type' => 'integer', 'null' => true],
		'pages' => ['type' => 'integer', 'null' => true]
	];

/**
 * records property
 *
 * @var array
 */
	public $records = [
		['id' => 1, 'isbn' => '1234567890', 'title' => 'Faust', 'author' => 'Johann Wolfgang von Goethe']
	];
}
