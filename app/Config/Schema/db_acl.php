<?php
/**
 * This is Acl Schema file
 *
 * Use it to configure database for ACL
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
 * @package       app.Config.Schema
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Using the Schema command line utility
 * cake schema run create DbAcl
 */
class DbAclSchema extends CakeSchema {

/**
 * Before event.
 *
 * @param array $event The event data.
 * @return bool Success
 */
	public function before($event = []) {
		return true;
	}

/**
 * After event.
 *
 * @param array $event The event data.
 * @return void
 */
	public function after($event = []) {
	}

/**
 * ACO - Access Control Object - Something that is wanted
 */
	public $acos = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
		'parent_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'model' => ['type' => 'string', 'null' => true],
		'foreign_key' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'alias' => ['type' => 'string', 'null' => true],
		'lft' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'rght' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'idx_acos_lft_rght' => ['column' => ['lft', 'rght'], 'unique' => 0],
			'idx_acos_alias' => ['column' => 'alias', 'unique' => 0]
		]
	];

/**
 * ARO - Access Request Object - Something that wants something
 */
	public $aros = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
		'parent_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'model' => ['type' => 'string', 'null' => true],
		'foreign_key' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'alias' => ['type' => 'string', 'null' => true],
		'lft' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'rght' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'idx_aros_lft_rght' => ['column' => ['lft', 'rght'], 'unique' => 0],
			'idx_aros_alias' => ['column' => 'alias', 'unique' => 0]
		]
	];

/**
 * Used by the Cake::Model:Permission class.
 * Checks if the given $aro has access to action $action in $aco.
 */
	public $aros_acos = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
		'aro_id' => ['type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'],
		'aco_id' => ['type' => 'integer', 'null' => false, 'length' => 10],
		'_create' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
		'_read' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
		'_update' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
		'_delete' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'ARO_ACO_KEY' => ['column' => ['aro_id', 'aco_id'], 'unique' => 1],
			'idx_aco_id' => ['column' => 'aco_id', 'unique' => 0]
		]
	];

}
