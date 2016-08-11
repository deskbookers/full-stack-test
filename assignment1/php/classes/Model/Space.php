<?php

class Model_Space extends ORM
{
	protected $_belongs_to = [
		'item' => ['model' => 'Item'],
	];
}
