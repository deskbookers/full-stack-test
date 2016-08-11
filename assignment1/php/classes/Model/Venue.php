<?php

class Model_Venue extends ORM
{
	protected $_has_many = [
		'items' => ['model' => 'Item'],
	];
}
