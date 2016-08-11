<?php

class ORM extends Kohana_ORM implements JsonSerializable
{
	static protected $_with_separator = ':';

	/**
	 * JSON data
	 * 
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->as_array();
	}

	/**
	 * Set with separator
	 * 
	 * @param string $separator
	 */
	public static function setWithSeparator($separator)
	{
		static::$_with_separator = (string) $separator;
	}

	/**
	 * Translate column name to underscore name
	 * 
	 * @param string $column
	 * @return string
	 */
	static public function translateColumnName($column)
	{
		if (static::$_with_separator != ':')
		{
			$column = str_replace(static::$_with_separator, ':', $column);
		}

		$column = Inflector::underscore(Inflector::decamelize($column));

		if (static::$_with_separator != ':')
		{
			$column = str_replace(':', static::$_with_separator, $column);
		}
		
		return $column;
	}

	/**
	 * Get with camel case support
	 * 
	 * @param string $column
	 * @return mixed
	 */
	/*
	public function get($column)
	{
		return parent::get(static::translateColumnName($column));
	}

	/**
	 * Set with camel case support
	 * 
	 * @param string $column
	 * @param mixed $value
	 * @return ORM This
	 */
	/*
	public function set($column, $value)
	{
		return parent::get(static::translateColumnName($column), $value);
	}
	*/

	/**
	 * Binds another one-to-one object to this model.  One-to-one objects
	 * can be nested using 'object1:object2' syntax
	 *
	 * @param  string|string[2] $target_path Target model to bind to, or array with target path and join type
	 * @return ORM
	 */
	public function with($target_path)
	{
		$joinType = 'LEFT';
		if (is_array($target_path) && count($target_path) >= 2)
		{
			$joinType = $target_path[1];
			$target_path = $target_path[0];
		}

		if (isset($this->_with_applied[$target_path]))
		{
			// Don't join anything already joined
			return $this;
		}

		// Split object parts
		$aliases = explode(static::$_with_separator, $target_path);
		$target = $this;
		foreach ($aliases as $alias)
		{
			// Go down the line of objects to find the given target
			$parent = $target;
			$target = $parent->_related($alias);

			if ( ! $target)
			{
				// Can't find related object
				return $this;
			}
		}

		// Target alias is at the end
		$target_alias = $alias;

		// Pop-off top alias to get the parent path (user:photo:tag becomes user:photo - the parent table prefix)
		array_pop($aliases);
		$parent_path = implode(static::$_with_separator, $aliases);

		if (empty($parent_path))
		{
			// Use this table name itself for the parent path
			$parent_path = $this->_object_name;
		}
		else
		{
			if ( ! isset($this->_with_applied[$parent_path]))
			{
				// If the parent path hasn't been joined yet, do it first (otherwise LEFT JOINs fail)
				$this->with([$parent_path, $joinType]);
			}
		}

		// Add to with_applied to prevent duplicate joins
		$this->_with_applied[$target_path] = TRUE;

		// Use the keys of the empty object to determine the columns
		foreach (array_keys($target->_object) as $column)
		{
			$name = $target_path.'.'.$column;
			$alias = $target_path.static::$_with_separator.$column;

			// Add the prefix so that load_result can determine the relationship
			$this->select(array($name, $alias));
		}

		if (isset($parent->_belongs_to[$target_alias]))
		{
			// Parent belongs_to target, use target's primary key and parent's foreign key
			$join_col1 = $target_path.'.'.$target->_primary_key;
			$join_col2 = $parent_path.'.'.$parent->_belongs_to[$target_alias]['foreign_key'];
		}
		else
		{
			// Parent has_one target, use parent's primary key as target's foreign key
			$join_col1 = $parent_path.'.'.$parent->_primary_key;
			$join_col2 = $target_path.'.'.$parent->_has_one[$target_alias]['foreign_key'];
		}

		// Join the related object into the result
		$this->join(array($target->_table_name, $target_path), $joinType)->on($join_col1, '=', $join_col2);

		return $this;
	}

	/**
	 * Loads an array of values into into the current object.
	 *
	 * @chainable
	 * @param  array $values Values to load
	 * @return ORM
	 */
	protected function _load_values(array $values)
	{
		if (array_key_exists($this->_primary_key, $values))
		{
			if ($values[$this->_primary_key] !== NULL)
			{
				// Flag as loaded and valid
				$this->_loaded = $this->_valid = TRUE;

				// Store primary key
				$this->_primary_key_value = $values[$this->_primary_key];
			}
			else
			{
				// Not loaded or valid
				$this->_loaded = $this->_valid = FALSE;
			}
		}

		// Related objects
		$related = array();

		foreach ($values as $column => $value)
		{
			if (strpos($column, static::$_with_separator) === FALSE)
			{
				// Load the value to this model
				$this->_object[$column] = $value;
			}
			else
			{
				// Column belongs to a related model
				list ($prefix, $column) = explode(static::$_with_separator, $column, 2);

				$related[$prefix][$column] = $value;
			}
		}

		if ( ! empty($related))
		{
			foreach ($related as $object => $values)
			{
				// Load the related objects with the values in the result
				$this->_related($object)->_load_values($values);
			}
		}

		if ($this->_loaded)
		{
			// Store the object in its original state
			$this->_original_values = $this->_object;
		}

		return $this;
	}
}
