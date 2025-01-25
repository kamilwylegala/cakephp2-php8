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
 * StoriesTagFixture
 *
 * @package       Cake.Test.Fixture
 */
class StoriesTagFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'story' => ['type' => 'integer', 'null' => false],
		'tag_id' => ['type' => 'integer', 'null' => false],
		'indexes' => ['UNIQUE_STORY_TAG' => ['column' => ['story', 'tag_id'], 'unique' => 1]]
	];

/**
 * records property
 *
 * @var array
 */
	public $records = [
		['story' => 1, 'tag_id' => 1]
	];
}
