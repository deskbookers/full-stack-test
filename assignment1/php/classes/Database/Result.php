<?php

abstract class Database_Result extends Kohana_Database_Result implements JsonSerializable
{
	/**
	 * JSON data
	 * 
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->as_array();
	}
}
