<?php

class Database_PDO extends Kohana_Database_PDO
{
	protected $_list_columns_cache = [];

	public function list_tables($like = NULL)
	{
		$this->_connection OR $this->connect();

		$sql = 'SELECT name FROM sqlite_master WHERE type = \'table\'';
		if (is_string($like))
		{
			$sql .= ' AND name LIKE ' . $this->quote($like);
		}

		return $this->query(Database::SELECT, $sql, false)
			->as_array(null, 'name')
		;
	}

	public function datatype($type)
	{
		static $types = [
			'integer'   => ['type' => 'int', 'min' => '-9223372036854775808', 'max' => '9223372036854775807'],
			'text'      => ['type' => 'string'],
		];
		$type = strtolower($type);
		if (isset($types[$type]))
		{
			return $types[$type];
		}
		return parent::datatype($type);
	}

	public function list_columns($table, $like = NULL, $add_prefix = TRUE)
	{
		$this->_connection OR $this->connect();

		$sql = 'PRAGMA table_info(' . ($add_prefix ? $this->table_prefix() . $table : $table) . ');';
		$key = md5($sql);
		if ( ! array_key_exists($key, $this->_list_columns_cache))
		{
			$columns = [];

			// Retrieve columns lines
			foreach ($this->query(Database::SELECT, $sql, false) as $col)
			{
				$column = Arr::get($col, 'name');
				$columns[$column] = Arr::merge(
					$col,
					$this->datatype(Arr::get($col, 'type')),
					[
						'column_name'    => $column,
						'is_nullable'    => Arr::get($col, 'notnull') != 0,
						'column_default' => Arr::get($col, 'dflt_value'),
					]
				);
			}

			$this->_list_columns_cache[$key] = $columns;
		}
		
		return $this->_list_columns_cache[$key];
	}
}
