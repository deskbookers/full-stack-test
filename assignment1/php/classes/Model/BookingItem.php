<?php

class Model_BookingItem extends ORM
{
	protected $_table_name = 'booking_items';
	protected $_belongs_to = [
		'item' => ['model' => 'Item'],
		'booking' => ['model' => 'Booking'],
	];
}
