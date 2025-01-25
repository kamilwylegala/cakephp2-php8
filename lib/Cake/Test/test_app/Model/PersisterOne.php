<?php
/**
 * Test App Comment Model
 *
 * CakePHP : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP Project
 * @package       Cake.Test.TestApp.Model
 * @since         CakePHP v 1.2.0.7726
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * PersisterOne
 *
 * @package       Cake.Test.TestApp.Model
 */
class PersisterOne extends AppModel {

	public $useTable = 'posts';

	public $actsAs = ['PersisterOneBehavior', 'TestPlugin.TestPluginPersisterOne'];

	public $hasMany = ['Comment', 'TestPlugin.TestPluginComment'];

	public $validate = [
		'title' => [
			'custom' => [
				'rule' => ['custom', '.*'],
				'allowEmpty' => true,
				'required' => false,
				'message' => 'Post title is required'
			],
			'between' => [
				'rule' => ['lengthBetween', 5, 15],
				'message' => ['You may enter up to %s chars (minimum is %s chars)', 14, 6]
			]
		],
		'body' => [
			'first_rule' => [
				'rule' => ['custom', '.*'],
				'allowEmpty' => true,
				'required' => false,
				'message' => 'Post body is required'
			],
			'second_rule' => [
				'rule' => ['custom', '.*'],
				'allowEmpty' => true,
				'required' => false,
				'message' => 'Post body is super required'
			]
		],
	];

}
