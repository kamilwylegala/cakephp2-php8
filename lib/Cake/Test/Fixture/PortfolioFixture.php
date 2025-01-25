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
 * PortfolioFixture
 *
 * @package       Cake.Test.Fixture
 */
class PortfolioFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer', 'key' => 'primary'],
		'seller_id' => ['type' => 'integer', 'null' => false],
		'name' => ['type' => 'string', 'null' => false]
	];

/**
 * records property
 *
 * @var array
 */
	public $records = [
		['seller_id' => 1, 'name' => 'Portfolio 1'],
		['seller_id' => 1, 'name' => 'Portfolio 2'],
		['seller_id' => 2, 'name' => 'Portfolio 1']
	];
}
