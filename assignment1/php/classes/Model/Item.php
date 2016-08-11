<?php

class Model_Item extends ORM
{
	protected $_belongs_to = [
		'venue' => ['model' => 'Venue'],
	];
	protected $_has_one = [
		'space' => ['model' => 'Space'],
		'product' => ['model' => 'Product'],
	];
}
