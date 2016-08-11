<?php

class Model_Booker extends ORM
{
	protected $_belongs_to = [
		'user' => ['model' => 'User'],
	];
	protected $_has_many = [
		'bookings' => ['model' => 'Booking'],
	];
}
