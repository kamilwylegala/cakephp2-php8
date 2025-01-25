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
 * OverallFavoriteFixture
 *
 * @package       Cake.Test.Fixture
 */
class OverallFavoriteFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer', 'key' => 'primary'],
		'model_type' => ['type' => 'string', 'length' => 255],
		'model_id' => ['type' => 'integer'],
		'priority' => ['type' => 'integer']
	];

/**
 * records property
 *
 * @var array
 */
	public $records = [
		['id' => 1, 'model_type' => 'Cd', 'model_id' => '1', 'priority' => '1'],
		['id' => 2, 'model_type' => 'Book', 'model_id' => '1', 'priority' => '2']
	];
}
