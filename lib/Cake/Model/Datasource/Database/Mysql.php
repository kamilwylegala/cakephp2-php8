<?php
/**
 * MySQL layer for DBO
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
 * @package       Cake.Model.Datasource.Database
 * @since         CakePHP(tm) v 0.10.5.1790
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('DboSource', 'Model/Datasource');

/**
 * MySQL DBO driver object
 *
 * Provides connection and SQL generation for MySQL RDMS
 *
 * @package       Cake.Model.Datasource.Database
 */
class Mysql extends DboSource {

/**
 * Datasource description
 *
 * @var string
 */
	public $description = "MySQL DBO Driver";

/**
 * Base configuration settings for MySQL driver
 *
 * @var array
 */
	protected $_baseConfig = [
		'persistent' => true,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'cake',
		'port' => '3306',
		'flags' => []
	];

/**
 * Reference to the PDO object connection
 *
 * @var PDO
 */
	protected $_connection = null;

/**
 * Start quote
 *
 * @var string
 */
	public $startQuote = "`";

/**
 * End quote
 *
 * @var string
 */
	public $endQuote = "`";

/**
 * use alias for update and delete. Set to true if version >= 4.1
 *
 * @var bool
 */
	protected $_useAlias = true;

/**
 * List of engine specific additional field parameters used on table creating
 *
 * @var array
 */
	public $fieldParameters = [
		'charset' => ['value' => 'CHARACTER SET', 'quote' => false, 'join' => ' ', 'column' => false, 'position' => 'beforeDefault'],
		'collate' => ['value' => 'COLLATE', 'quote' => false, 'join' => ' ', 'column' => 'Collation', 'position' => 'beforeDefault'],
		'comment' => ['value' => 'COMMENT', 'quote' => true, 'join' => ' ', 'column' => 'Comment', 'position' => 'afterDefault'],
		'unsigned' => [
			'value' => 'UNSIGNED',
			'quote' => false,
			'join' => ' ',
			'column' => false,
			'position' => 'beforeDefault',
			'noVal' => true,
			'options' => [true],
			'types' => ['integer', 'smallinteger', 'tinyinteger', 'float', 'decimal', 'biginteger']
		]
	];

/**
 * List of table engine specific parameters used on table creating
 *
 * @var array
 */
	public $tableParameters = [
		'charset' => ['value' => 'DEFAULT CHARSET', 'quote' => false, 'join' => '=', 'column' => 'charset'],
		'collate' => ['value' => 'COLLATE', 'quote' => false, 'join' => '=', 'column' => 'Collation'],
		'engine' => ['value' => 'ENGINE', 'quote' => false, 'join' => '=', 'column' => 'Engine'],
		'comment' => ['value' => 'COMMENT', 'quote' => true, 'join' => '=', 'column' => 'Comment'],
	];

/**
 * MySQL column definition
 *
 * @var array
 * @link https://dev.mysql.com/doc/refman/5.7/en/data-types.html MySQL Data Types
 */
	public $columns = [
		'primary_key' => ['name' => 'NOT NULL AUTO_INCREMENT'],
		'string' => ['name' => 'varchar', 'limit' => '255'],
		'text' => ['name' => 'text'],
		'enum' => ['name' => 'enum'],
		'biginteger' => ['name' => 'bigint', 'limit' => '20'],
		'integer' => ['name' => 'int', 'limit' => '11', 'formatter' => 'intval'],
		'smallinteger' => ['name' => 'smallint', 'limit' => '6', 'formatter' => 'intval'],
		'tinyinteger' => ['name' => 'tinyint', 'limit' => '4', 'formatter' => 'intval'],
		'float' => ['name' => 'float', 'formatter' => 'floatval'],
		'decimal' => ['name' => 'decimal', 'formatter' => 'floatval'],
		'datetime' => ['name' => 'datetime', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
		'timestamp' => ['name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'],
		'time' => ['name' => 'time', 'format' => 'H:i:s', 'formatter' => 'date'],
		'date' => ['name' => 'date', 'format' => 'Y-m-d', 'formatter' => 'date'],
		'binary' => ['name' => 'blob'],
		'boolean' => ['name' => 'tinyint', 'limit' => '1']
	];

/**
 * Mapping of collation names to character set names
 *
 * @var array
 */
	protected $_charsets = [];

/**
 * Connects to the database using options in the given configuration array.
 *
 * MySQL supports a few additional options that other drivers do not:
 *
 * - `unix_socket` Set to the path of the MySQL sock file. Can be used in place
 *   of host + port.
 * - `ssl_key` SSL key file for connecting via SSL. Must be combined with `ssl_cert`.
 * - `ssl_cert` The SSL certificate to use when connecting via SSL. Must be
 *   combined with `ssl_key`.
 * - `ssl_ca` The certificate authority for SSL connections.
 *
 * @return bool True if the database could be connected, else false
 * @throws MissingConnectionException
 */
	public function connect() {
		$config = $this->config;
		$this->connected = false;

		$flags = $config['flags'] + [
			PDO::ATTR_PERSISTENT => $config['persistent'],
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];

		if (!empty($config['encoding'])) {
			$flags[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $config['encoding'];
		}
		if (!empty($config['ssl_key']) && !empty($config['ssl_cert'])) {
			$flags[PDO::MYSQL_ATTR_SSL_KEY] = $config['ssl_key'];
			$flags[PDO::MYSQL_ATTR_SSL_CERT] = $config['ssl_cert'];
		}
		if (!empty($config['ssl_ca'])) {
			$flags[PDO::MYSQL_ATTR_SSL_CA] = $config['ssl_ca'];
		}
		if (empty($config['unix_socket'])) {
			$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
		} else {
			$dsn = "mysql:unix_socket={$config['unix_socket']};dbname={$config['database']}";
		}

		try {
			$this->_connection = new PDO(
				$dsn,
				$config['login'],
				$config['password'],
				$flags
			);
			$this->connected = true;
			if (!empty($config['settings'])) {
				foreach ($config['settings'] as $key => $value) {
					$this->_execute("SET $key=$value");
				}
			}
		} catch (PDOException $e) {
			throw new MissingConnectionException([
				'class' => static::class,
				'message' => $e->getMessage()
			]);
		}

		$this->_charsets = [];
		$this->_useAlias = (bool)version_compare($this->getVersion(), "4.1", ">=");

		return $this->connected;
	}

/**
 * Check whether the MySQL extension is installed/loaded
 *
 * @return bool
 */
	public function enabled() {
		return in_array('mysql', PDO::getAvailableDrivers());
	}

/**
 * Returns an array of sources (tables) in the database.
 *
 * @param mixed $data List of tables.
 * @return array Array of table names in the database
 */
	public function listSources($data = null) {
		$cache = parent::listSources();
		if ($cache) {
			return $cache;
		}
		$result = $this->_execute('SHOW TABLES FROM ' . $this->name($this->config['database']));

		if (!$result) {
			$result->closeCursor();
			return [];
		}
		$tables = [];

		while ($line = $result->fetch(PDO::FETCH_NUM)) {
			$tables[] = $line[0];
		}

		$result->closeCursor();
		parent::listSources($tables);
		return $tables;
	}

/**
 * Builds a map of the columns contained in a result
 *
 * @param PDOStatement $results The results to format.
 * @return void
 */
	public function resultSet($results) {
		$this->map = [];
		$numFields = $results->columnCount();
		$index = 0;

		while ($numFields-- > 0) {
			$column = $results->getColumnMeta($index);
			if ($column['len'] === 1 && (empty($column['native_type']) || $column['native_type'] === 'TINY')) {
				$type = 'boolean';
			} else {
				$type = empty($column['native_type']) ? 'string' : $column['native_type'];
			}
			if (!empty($column['table']) && !str_contains($column['name'], $this->virtualFieldSeparator)) {
				$this->map[$index++] = [$column['table'], $column['name'], $type];
			} else {
				$this->map[$index++] = [0, $column['name'], $type];
			}
		}
	}

/**
 * Fetches the next row from the current result set
 *
 * @return mixed array with results fetched and mapped to column names or false if there is no results left to fetch
 */
	public function fetchResult() {
		if ($row = $this->_result->fetch(PDO::FETCH_NUM)) {
			$resultRow = [];
			foreach ($this->map as $col => $meta) {
				[$table, $column, $type] = $meta;
				$resultRow[$table][$column] = $row[$col];
				if ($type === 'boolean' && $row[$col] !== null) {
					$resultRow[$table][$column] = $this->boolean($resultRow[$table][$column]);
				}
			}
			return $resultRow;
		}
		$this->_result->closeCursor();
		return false;
	}

/**
 * Gets the database encoding
 *
 * @return string The database encoding
 */
	public function getEncoding() {
		return $this->_execute('SHOW VARIABLES LIKE ?', ['character_set_client'])->fetchObject()->Value;
	}

/**
 * Query charset by collation
 *
 * @param string $name Collation name
 * @return string|false Character set name
 */
	public function getCharsetName($name) {
		if ((bool)version_compare($this->getVersion(), "5", "<")) {
			return false;
		}
		if (isset($this->_charsets[$name])) {
			return $this->_charsets[$name];
		}
		$r = $this->_execute(
			'SELECT CHARACTER_SET_NAME FROM INFORMATION_SCHEMA.COLLATIONS WHERE COLLATION_NAME = ?',
			[$name]
		);
		$cols = $r->fetch(PDO::FETCH_ASSOC);

		if (isset($cols['CHARACTER_SET_NAME'])) {
			$this->_charsets[$name] = $cols['CHARACTER_SET_NAME'];
		} else {
			$this->_charsets[$name] = false;
		}
		return $this->_charsets[$name];
	}

/**
 * Returns an array of the fields in given table name.
 *
 * @param Model|string $model Name of database table to inspect or model instance
 * @return array|bool Fields in table. Keys are name and type. Returns false if result is empty.
 * @throws CakeException
 */
	public function describe($model) {
		$key = $this->fullTableName($model, false);
		$cache = parent::describe($key);
		if ($cache) {
			return $cache;
		}
		$table = $this->fullTableName($model);

		$fields = [];
		$cols = $this->_execute('SHOW FULL COLUMNS FROM ' . $table);
		if (!$cols) {
			throw new CakeException(__d('cake_dev', 'Could not describe table for %s', $table));
		}

		while ($column = $cols->fetch(PDO::FETCH_OBJ)) {
			$fields[$column->Field] = [
				'type' => $this->column($column->Type),
				'null' => ($column->Null === 'YES' ? true : false),
				'default' => $column->Default,
				'length' => $this->length($column->Type)
			];
			if (in_array($fields[$column->Field]['type'], $this->fieldParameters['unsigned']['types'], true)) {
				$fields[$column->Field]['unsigned'] = $this->_unsigned($column->Type);
			}
			if (in_array($fields[$column->Field]['type'], ['timestamp', 'datetime']) &&
				//Falling back to default empty string due to PHP8.1 deprecation notice.
				in_array(strtoupper($column->Default ?? ""), ['CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()'])
			) {
				$fields[$column->Field]['default'] = null;
			}
			if (!empty($column->Key) && isset($this->index[$column->Key])) {
				$fields[$column->Field]['key'] = $this->index[$column->Key];
			}
			foreach ($this->fieldParameters as $name => $value) {
				if (!empty($column->{$value['column']})) {
					$fields[$column->Field][$name] = $column->{$value['column']};
				}
			}
			if (isset($fields[$column->Field]['collate'])) {
				$charset = $this->getCharsetName($fields[$column->Field]['collate']);
				if ($charset) {
					$fields[$column->Field]['charset'] = $charset;
				}
			}
		}
		$this->_cacheDescription($key, $fields);
		$cols->closeCursor();

		//Fields must be an array for compatibility with PHP8.1 (deprecation notice) but also let's keep backwards compatibility for method.
		if (count($fields) === 0) {
			return false;
		}

		return $fields;
	}

/**
 * Generates and executes an SQL UPDATE statement for given model, fields, and values.
 *
 * @param Model $model The model to update.
 * @param array $fields The fields to update.
 * @param array $values The values to set.
 * @param mixed $conditions The conditions to use.
 * @return bool
 */
	public function update(Model $model, $fields = [], $values = null, $conditions = null) {
		if (!$this->_useAlias) {
			return parent::update($model, $fields, $values, $conditions);
		}

		if (!$values) {
			$combined = $fields;
		} else {
			$combined = array_combine($fields, $values);
		}

		$alias = $joins = false;
		$fields = $this->_prepareUpdateFields($model, $combined, empty($conditions), !empty($conditions));
		$fields = implode(', ', $fields);
		$table = $this->fullTableName($model);

		if (!empty($conditions)) {
			$alias = $this->name($model->alias);
			if ($model->name === $model->alias) {
				$joins = implode(' ', $this->_getJoins($model));
			}
		}
		$conditions = $this->conditions($this->defaultConditions($model, $conditions, $alias), true, true, $model);

		if ($conditions === false) {
			return false;
		}

		if (!$this->execute($this->renderStatement('update', compact('table', 'alias', 'joins', 'fields', 'conditions')))) {
			$model->onError();
			return false;
		}
		return true;
	}

/**
 * Generates and executes an SQL DELETE statement for given id/conditions on given model.
 *
 * @param Model $model The model to delete from.
 * @param mixed $conditions The conditions to use.
 * @return bool Success
 */
	public function delete(Model $model, $conditions = null) {
		if (!$this->_useAlias) {
			return parent::delete($model, $conditions);
		}
		$alias = $this->name($model->alias);
		$table = $this->fullTableName($model);
		$joins = implode(' ', $this->_getJoins($model));

		if (empty($conditions)) {
			$alias = $joins = false;
		}
		$complexConditions = $this->_deleteNeedsComplexConditions($model, $conditions);
		if (!$complexConditions) {
			$joins = false;
		}

		$conditions = $this->conditions($this->defaultConditions($model, $conditions, $alias), true, true, $model);
		if ($conditions === false) {
			return false;
		}
		if ($this->execute($this->renderStatement('delete', compact('alias', 'table', 'joins', 'conditions'))) === false) {
			$model->onError();
			return false;
		}
		return true;
	}

/**
 * Checks whether complex conditions are needed for a delete with the given conditions.
 *
 * @param Model $model The model to delete from.
 * @param mixed $conditions The conditions to use.
 * @return bool Whether or not complex conditions are needed
 */
	protected function _deleteNeedsComplexConditions(Model $model, $conditions) {
		$fields = array_keys($this->describe($model));
		foreach ((array)$conditions as $key => $value) {
			if (in_array(strtolower(trim($key)), $this->_sqlBoolOps, true)) {
				if ($this->_deleteNeedsComplexConditions($model, $value)) {
					return true;
				}
			} elseif (!str_contains($key, $model->alias) && !in_array($key, $fields, true)) {
				return true;
			}
		}
		return false;
	}

/**
 * Sets the database encoding
 *
 * @param string $enc Database encoding
 * @return bool
 */
	public function setEncoding($enc) {
		return $this->_execute('SET NAMES ' . $enc) !== false;
	}

/**
 * Returns an array of the indexes in given datasource name.
 *
 * @param string $model Name of model to inspect
 * @return array Fields in table. Keys are column and unique
 */
	public function index($model) {
		$index = [];
		$table = $this->fullTableName($model);
		$old = version_compare($this->getVersion(), '4.1', '<=');
		if ($table) {
			$indexes = $this->_execute('SHOW INDEX FROM ' . $table);
			// @codingStandardsIgnoreStart
			// MySQL columns don't match the cakephp conventions.
			while ($idx = $indexes->fetch(PDO::FETCH_OBJ)) {
				if ($old) {
					$idx = (object)current((array)$idx);
				}
				if (!isset($index[$idx->Key_name]['column'])) {
					$col = [];
					$index[$idx->Key_name]['column'] = $idx->Column_name;

					if ($idx->Index_type === 'FULLTEXT') {
						$index[$idx->Key_name]['type'] = strtolower($idx->Index_type);
					} else {
						$index[$idx->Key_name]['unique'] = (int)($idx->Non_unique == 0);
					}
				} else {
					if (!empty($index[$idx->Key_name]['column']) && !is_array($index[$idx->Key_name]['column'])) {
						$col[] = $index[$idx->Key_name]['column'];
					}
					$col[] = $idx->Column_name;
					$index[$idx->Key_name]['column'] = $col;
				}
				if (!empty($idx->Sub_part)) {
					if (!isset($index[$idx->Key_name]['length'])) {
						$index[$idx->Key_name]['length'] = [];
					}
					$index[$idx->Key_name]['length'][$idx->Column_name] = $idx->Sub_part;
				}
			}
			// @codingStandardsIgnoreEnd
			$indexes->closeCursor();
		}
		return $index;
	}

/**
 * Generate a MySQL Alter Table syntax for the given Schema comparison
 *
 * @param array $compare Result of a CakeSchema::compare()
 * @param string $table The table name.
 * @return string|false String of alter statements to make.
 */
	public function alterSchema($compare, $table = null) {
		if (!is_array($compare)) {
			return false;
		}
		$out = '';
		$colList = [];
		foreach ($compare as $curTable => $types) {
			$indexes = $tableParameters = $colList = [];
			if (!$table || $table === $curTable) {
				$out .= 'ALTER TABLE ' . $this->fullTableName($curTable) . " \n";
				foreach ($types as $type => $column) {
					if (isset($column['indexes'])) {
						$indexes[$type] = $column['indexes'];
						unset($column['indexes']);
					}
					if (isset($column['tableParameters'])) {
						$tableParameters[$type] = $column['tableParameters'];
						unset($column['tableParameters']);
					}
					switch ($type) {
						case 'add':
							foreach ($column as $field => $col) {
								$col['name'] = $field;
								$alter = 'ADD ' . $this->buildColumn($col);
								if (isset($col['after'])) {
									$alter .= ' AFTER ' . $this->name($col['after']);
								}
								$colList[] = $alter;
							}
							break;
						case 'drop':
							foreach ($column as $field => $col) {
								$col['name'] = $field;
								$colList[] = 'DROP ' . $this->name($field);
							}
							break;
						case 'change':
							foreach ($column as $field => $col) {
								if (!isset($col['name'])) {
									$col['name'] = $field;
								}
								$alter = 'CHANGE ' . $this->name($field) . ' ' . $this->buildColumn($col);
								if (isset($col['after'])) {
									$alter .= ' AFTER ' . $this->name($col['after']);
								}
								$colList[] = $alter;
							}
							break;
					}
				}
				$colList = array_merge($colList, $this->_alterIndexes($curTable, $indexes));
				$colList = array_merge($colList, $this->_alterTableParameters($curTable, $tableParameters));
				$out .= "\t" . implode(",\n\t", $colList) . ";\n\n";
			}
		}
		return $out;
	}

/**
 * Generate a "drop table" statement for the given table
 *
 * @param type $table Name of the table to drop
 * @return string Drop table SQL statement
 */
	protected function _dropTable($table) {
		return 'DROP TABLE IF EXISTS ' . $this->fullTableName($table) . ";";
	}

/**
 * Generate MySQL table parameter alteration statements for a table.
 *
 * @param string $table Table to alter parameters for.
 * @param array $parameters Parameters to add & drop.
 * @return array Array of table property alteration statements.
 */
	protected function _alterTableParameters($table, $parameters) {
		if (isset($parameters['change'])) {
			return $this->buildTableParameters($parameters['change']);
		}
		return [];
	}

/**
 * Format indexes for create table
 *
 * @param array $indexes An array of indexes to generate SQL from
 * @param string $table Optional table name, not used
 * @return array An array of SQL statements for indexes
 * @see DboSource::buildIndex()
 */
	public function buildIndex($indexes, $table = null) {
		$join = [];
		foreach ($indexes as $name => $value) {
			$out = '';
			if ($name === 'PRIMARY') {
				$out .= 'PRIMARY ';
				$name = null;
			} else {
				if (!empty($value['unique'])) {
					$out .= 'UNIQUE ';
				}
				$name = $this->startQuote . $name . $this->endQuote;
			}
			if (isset($value['type']) && strtolower($value['type']) === 'fulltext') {
				$out .= 'FULLTEXT ';
			}
			$out .= 'KEY ' . $name . ' (';

			if (is_array($value['column'])) {
				if (isset($value['length'])) {
					$vals = [];
					foreach ($value['column'] as $column) {
						$name = $this->name($column);
						if (isset($value['length'])) {
							$name .= $this->_buildIndexSubPart($value['length'], $column);
						}
						$vals[] = $name;
					}
					$out .= implode(', ', $vals);
				} else {
					$out .= implode(', ', array_map([&$this, 'name'], $value['column']));
				}
			} else {
				$out .= $this->name($value['column']);
				if (isset($value['length'])) {
					$out .= $this->_buildIndexSubPart($value['length'], $value['column']);
				}
			}
			$out .= ')';
			$join[] = $out;
		}
		return $join;
	}

/**
 * Generate MySQL index alteration statements for a table.
 *
 * @param string $table Table to alter indexes for
 * @param array $indexes Indexes to add and drop
 * @return array Index alteration statements
 */
	protected function _alterIndexes($table, $indexes) {
		$alter = [];
		if (isset($indexes['drop'])) {
			foreach ($indexes['drop'] as $name => $value) {
				$out = 'DROP ';
				if ($name === 'PRIMARY') {
					$out .= 'PRIMARY KEY';
				} else {
					$out .= 'KEY ' . $this->startQuote . $name . $this->endQuote;
				}
				$alter[] = $out;
			}
		}
		if (isset($indexes['add'])) {
			$add = $this->buildIndex($indexes['add']);
			foreach ($add as $index) {
				$alter[] = 'ADD ' . $index;
			}
		}
		return $alter;
	}

/**
 * Format length for text indexes
 *
 * @param array $lengths An array of lengths for a single index
 * @param string $column The column for which to generate the index length
 * @return string Formatted length part of an index field
 */
	protected function _buildIndexSubPart($lengths, $column) {
		if ($lengths === null) {
			return '';
		}
		if (!isset($lengths[$column])) {
			return '';
		}
		return '(' . $lengths[$column] . ')';
	}

/**
 * Returns a detailed array of sources (tables) in the database.
 *
 * @param string $name Table name to get parameters
 * @return array Array of table names in the database
 */
	public function listDetailedSources($name = null) {
		$condition = '';
		if (is_string($name)) {
			$condition = ' WHERE name = ' . $this->value($name);
		}
		$result = $this->_connection->query('SHOW TABLE STATUS ' . $condition, PDO::FETCH_ASSOC);

		if (!$result) {
			$result->closeCursor();
			return [];
		}
		$tables = [];
		foreach ($result as $row) {
			$tables[$row['Name']] = (array)$row;
			unset($tables[$row['Name']]['queryString']);
			if (!empty($row['Collation'])) {
				$charset = $this->getCharsetName($row['Collation']);
				if ($charset) {
					$tables[$row['Name']]['charset'] = $charset;
				}
			}
		}
		$result->closeCursor();
		if (is_string($name) && isset($tables[$name])) {
			return $tables[$name];
		}
		return $tables;
	}

/**
 * Converts database-layer column types to basic types
 *
 * @param string $real Real database-layer column type (i.e. "varchar(255)")
 * @return string Abstract column type (i.e. "string")
 */
	public function column($real) {
		if (is_array($real)) {
			$col = $real['name'];
			if (isset($real['limit'])) {
				$col .= '(' . $real['limit'] . ')';
			}
			return $col;
		}

		$col = str_replace(')', '', $real);
		$limit = $this->length($real);
		if (str_contains($col, '(')) {
			[$col, $vals] = explode('(', $col);
		}

		if (in_array($col, ['date', 'time', 'datetime', 'timestamp'])) {
			return $col;
		}
		if (($col === 'tinyint' && $limit === 1) || $col === 'boolean') {
			return 'boolean';
		}
		if (str_contains($col, 'bigint') || $col === 'bigint') {
			return 'biginteger';
		}
		if (str_contains($col, 'tinyint')) {
			return 'tinyinteger';
		}
		if (str_contains($col, 'smallint')) {
			return 'smallinteger';
		}
		if (str_contains($col, 'int')) {
			return 'integer';
		}
		if (str_contains($col, 'char') || $col === 'tinytext') {
			return 'string';
		}
		if (str_contains($col, 'text')) {
			return 'text';
		}
		if (str_contains($col, 'blob') || $col === 'binary') {
			return 'binary';
		}
		if (str_contains($col, 'float') || str_contains($col, 'double')) {
			return 'float';
		}
		if (str_contains($col, 'decimal') || str_contains($col, 'numeric')) {
			return 'decimal';
		}
		if (str_contains($col, 'enum')) {
			return "enum($vals)";
		}
		if (str_contains($col, 'set')) {
			return "set($vals)";
		}
		return 'text';
	}

/**
 * {@inheritDoc}
 */
	public function value($data, $column = null, $null = true) {
		$value = parent::value($data, $column, $null);
		if (is_numeric($value) && $column !== null && str_starts_with($column, 'set')) {
			return $this->_connection->quote($value);
		}
		return $value;
	}

/**
 * Gets the schema name
 *
 * @return string The schema name
 */
	public function getSchemaName() {
		return $this->config['database'];
	}

/**
 * Check if the server support nested transactions
 *
 * @return bool
 */
	public function nestedTransactionSupported() {
		return $this->useNestedTransactions && version_compare($this->getVersion(), '4.1', '>=');
	}

/**
 * Check if column type is unsigned
 *
 * @param string $real Real database-layer column type (i.e. "varchar(255)")
 * @return bool True if column is unsigned, false otherwise
 */
	protected function _unsigned($real) {
		return str_contains(strtolower($real), 'unsigned');
	}

/**
 * Inserts multiple values into a table. Uses a single query in order to insert
 * multiple rows.
 *
 * @param string $table The table being inserted into.
 * @param array $fields The array of field/column names being inserted.
 * @param array $values The array of values to insert. The values should
 *   be an array of rows. Each row should have values keyed by the column name.
 *   Each row must have the values in the same order as $fields.
 * @return bool
 */
	public function insertMulti($table, $fields, $values) {
		$table = $this->fullTableName($table);
		$holder = implode(', ', array_fill(0, count($fields), '?'));
		$fields = implode(', ', array_map([$this, 'name'], $fields));
		$pdoMap = [
			'integer' => PDO::PARAM_INT,
			'float' => PDO::PARAM_STR,
			'boolean' => PDO::PARAM_BOOL,
			'string' => PDO::PARAM_STR,
			'text' => PDO::PARAM_STR
		];
		$columnMap = [];
		$rowHolder = "({$holder})";
		$sql = "INSERT INTO {$table} ({$fields}) VALUES ";
		$countRows = count($values);
		for ($i = 0; $i < $countRows; $i++) {
			if ($i !== 0) {
				$sql .= ',';
			}
			$sql .= " $rowHolder";
		}
		$statement = $this->_connection->prepare($sql);
		foreach ($values[key($values)] as $key => $val) {
			$type = $this->introspectType($val);
			$columnMap[$key] = $pdoMap[$type];
		}
		$valuesList = [];
		$i = 1;
		foreach ($values as $value) {
			foreach ($value as $col => $val) {
				$valuesList[] = $val;
				$statement->bindValue($i, $val, $columnMap[$col]);
				$i++;
			}
		}
		$result = $statement->execute();
		$statement->closeCursor();
		if ($this->fullDebug) {
			$this->logQuery($sql, $valuesList);
		}
		return $result;
	}
}
