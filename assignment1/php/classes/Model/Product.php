<?php

class Model_Product extends ORM
{
	protected $_belongs_to = [
		'item' => ['model' => 'Item'],
	];
}
