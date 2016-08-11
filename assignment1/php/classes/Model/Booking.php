<?php

class Model_Booking extends ORM
{
	protected $_belongs_to = [
		'booker' => ['model' => 'Booker'],
	];
	protected $_has_many = [
		'bookedItems' => ['model' => 'BookingItem'],
	];
}
