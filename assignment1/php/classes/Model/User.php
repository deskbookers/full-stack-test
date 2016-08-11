<?php

class Model_User extends ORM
{
	protected $_has_one = [
		'booker' => ['model' => 'Booker'],
	];
}
