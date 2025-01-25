<?php
/**
 * Tests cross database HABTM. Requires $test and $test2 to both be set in DATABASE_CONFIG
 * NOTE: When testing on MySQL, you must set 'persistent' => false on *both* database connections,
 * or one connection will step on the other.
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
 * @package       Cake.Test.Case.Model
 * @since         CakePHP(tm) v 2.1
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

require_once __DIR__ . DS . 'ModelTestBase.php';

/**
 * ModelCrossSchemaHabtmTest
 *
 * @package       Cake.Test.Case.Model
 */
class ModelCrossSchemaHabtmTest extends BaseModelTest {

/**
 * Fixtures to be used
 *
 * @var array
 */
	public $fixtures = [
		'core.player', 'core.guild', 'core.guilds_player',
		'core.armor', 'core.armors_player',
	];

/**
 * Don't drop tables if they exist
 *
 * @var bool
 */
	public $dropTables = false;

/**
 * Don't auto load fixtures
 *
 * @var bool
 */
	public $autoFixtures = false;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() : void {
		parent::setUp();
		$this->_checkConfigs();
	}

/**
 * Check if primary and secondary test databases are configured.
 *
 * @return void
 */
	protected function _checkConfigs() {
		$config = ConnectionManager::enumConnectionObjects();
		$this->skipIf($this->db instanceof Sqlite, 'This test is not compatible with Sqlite.');
		$this->skipIf(
			!isset($config['test']) || !isset($config['test2']),
			'Primary and secondary test databases not configured, ' .
			'skipping cross-database join tests.' .
			' To run these tests, you must define $test and $test2 in your database configuration.'
		);
	}

/**
 * testModelDatasources method
 *
 * @return void
 */
	public function testModelDatasources() {
		$this->loadFixtures('Player', 'Guild', 'GuildsPlayer');

		$Player = ClassRegistry::init('Player');
		$this->assertEquals('test', $Player->useDbConfig);
		$this->assertEquals('test', $Player->Guild->useDbConfig);
		$this->assertEquals('test2', $Player->GuildsPlayer->useDbConfig);

		$this->assertEquals('test', $Player->getDataSource()->configKeyName);
		$this->assertEquals('test', $Player->Guild->getDataSource()->configKeyName);
		$this->assertEquals('test2', $Player->GuildsPlayer->getDataSource()->configKeyName);
	}

/**
 * testHabtmFind method
 *
 * @return void
 */
	public function testHabtmFind() {
		$this->loadFixtures('Player', 'Guild', 'GuildsPlayer');
		$Player = ClassRegistry::init('Player');

		$players = $Player->find('all', [
			'fields' => ['id', 'name'],
			'contain' => [
				'Guild' => [
					'conditions' => [
						'Guild.name' => 'Wizards',
					],
				],
			],
		]);
		$this->assertEquals(4, count($players));
		$wizards = Hash::extract($players, '{n}.Guild.{n}[name=Wizards]');
		$this->assertEquals(1, count($wizards));

		$players = $Player->find('all', [
			'fields' => ['id', 'name'],
			'conditions' => [
				'Player.id' => 1,
			],
		]);
		$this->assertEquals(1, count($players));
		$wizards = Hash::extract($players, '{n}.Guild.{n}');
		$this->assertEquals(2, count($wizards));
	}

/**
 * testHabtmSave method
 *
 * @return void
 */
	public function testHabtmSave() {
		$this->loadFixtures('Player', 'Guild', 'GuildsPlayer');
		$Player = ClassRegistry::init('Player');
		$players = $Player->find('count');
		$this->assertEquals(4, $players);

		$player = $Player->create([
			'name' => 'rchavik',
		]);

		$results = $Player->saveAll($player, ['validate' => 'first']);
		$this->assertNotSame(false, $results);
		$count = $Player->find('count');
		$this->assertEquals(5, $count);

		$count = $Player->GuildsPlayer->find('count');
		$this->assertEquals(3, $count);

		$player = $Player->findByName('rchavik');
		$this->assertEmpty($player['Guild']);

		$player['Guild']['Guild'] = [1, 2, 3];
		$Player->save($player);

		$player = $Player->findByName('rchavik');
		$this->assertEquals(3, count($player['Guild']));

		$players = $Player->find('all', [
			'contain' => [
				'conditions' => [
					'Guild.name' => 'Rangers',
				],
			],
		]);
		$rangers = Hash::extract($players, '{n}.Guild.{n}[name=Rangers]');
		$this->assertEquals(2, count($rangers));
	}

/**
 * testHabtmWithThreeDatabases method
 *
 * @return void
 */
	public function testHabtmWithThreeDatabases() {
		$config = ConnectionManager::enumConnectionObjects();
		$this->skipIf(
			!isset($config['test']) || !isset($config['test2']) || !isset($config['test_database_three']),
			'Primary, secondary, and tertiary test databases not configured,' .
			' skipping test. To run these tests, you must define ' .
			'$test, $test2, and $test_database_three in your database configuration.'
		);

		$this->loadFixtures('Player', 'Guild', 'GuildsPlayer', 'Armor', 'ArmorsPlayer');

		$Player = ClassRegistry::init('Player');
		$Player->bindModel([
			'hasAndBelongsToMany' => [
				'Armor' => [
					'with' => 'ArmorsPlayer',
					'unique' => true,
				],
			],
		], false);
		$this->assertEquals('test', $Player->useDbConfig);
		$this->assertEquals('test2', $Player->Armor->useDbConfig);
		$this->assertEquals('test_database_three', $Player->ArmorsPlayer->useDbConfig);
		$players = $Player->find('count');
		$this->assertEquals(4, $players);

		$spongebob = $Player->create([
			'id' => 10,
			'name' => 'spongebob',
		]);
		$spongebob['Armor'] = ['Armor' => [1, 2, 3, 4]];
		$result = $Player->save($spongebob);

		$expected = [
			'Player' => [
				'id' => 10,
				'name' => 'spongebob',
			],
			'Armor' => [
				'Armor' => [
					1, 2, 3, 4,
				],
			],
		];
		unset($result['Player']['created']);
		unset($result['Player']['updated']);
		$this->assertEquals($expected, $result);

		$spongebob = $Player->find('all', [
			'conditions' => [
				'Player.id' => 10,
			]
		]);
		$spongeBobsArmors = Hash::extract($spongebob, '{n}.Armor.{n}');
		$this->assertEquals(4, count($spongeBobsArmors));
	}
}
