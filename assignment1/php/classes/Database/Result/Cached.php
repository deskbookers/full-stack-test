<?php

class Database_Result_Cached extends Kohana_Database_Result_Cached implements JsonSerializable
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
