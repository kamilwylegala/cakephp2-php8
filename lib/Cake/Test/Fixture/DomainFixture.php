<?php
/**
 * Short description for file.
 *
 * CakePHP(tm) Tests <https://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Fixture
 * @since         CakePHP(tm) v 2.1
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Short description for class.
 *
 * @package       Cake.Test.Fixture
 */
class DomainFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer', 'key' => 'primary'],
		'domain' => ['type' => 'string', 'null' => false],
		'created' => 'datetime',
		'updated' => 'datetime'
	];

/**
 * records property
 *
 * @var array
 */
	public $records = [
		['domain' => 'cakephp.org', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
		['domain' => 'book.cakephp.org', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
		['domain' => 'api.cakephp.org', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
		['domain' => 'mark-story.com', 'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'],
		['domain' => 'tinadurocher.com', 'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'],
		['domain' => 'chavik.com', 'created' => '2001-02-03 00:01:02', 'updated' => '2007-03-17 01:22:31'],
		['domain' => 'xintesa.com', 'created' => '2001-02-03 00:01:02', 'updated' => '2007-03-17 01:22:31'],
	];
}
