<?php
require_once(__DIR__ . '/bootstrap.php');

// Because SQLite doesn't allow ':' in table/column names
// we need to replace this char for something else: __
ORM::setWithSeparator('__');

/**
 * Basic API controller
 * 
 * This controller contains some basic API functionalities.
 * By the routes at the bottom of this page the controller's actions become accessible.
 */
class Controller_API extends Controller
{
	/**
	 * Get list of bookers
	 * 
	 * @see Controller_API::rows
	 */
	public function action_bookers()
	{
		$this->response->json($this->rows(
			ORM::factory('Booker')
				->with('user')
		));
	}

	/**
	 * Get a booker
	 */
	public function action_booker()
	{
		$this->response->json(
			ORM::factory('Booker')
				->where('booker.id', '=', (int) $this->request->param('id'))
				->with('user')
		);
	}

	/**
	 * Get list of venues
	 * 
	 * @see Controller_API::rows
	 */
	public function action_venues()
	{
		$this->response->json($this->rows(
			ORM::factory('Venue')
		));
	}

	/**
	 * Get a venue
	 * 
	 * @see Controller_API::rows
	 */
	public function action_venue()
	{
		$this->response->json(
			ORM::factory('Venue', (int) $this->request->param('id'))
		);
	}

	/**
	 * Get list of bookings
	 * 
	 * @see Controller_API::rows
	 */
	public function action_bookings()
	{
		$this->response->json($this->rows(
			ORM::factory('Booking')
				->with('booker__user')
			,
			[$this, 'with_booking']
		));
	}

	/**
	 * Get a booking
	 * 
	 * @see Controller_API::rows
	 */
	public function action_booking()
	{
		$this->response->json($this->with(
			ORM::factory('Booking')
				->where('booking.id', '=', (int) $this->request->param('id'))
				->with('booker__user')
				->find()
			,
			[$this, 'with_booking']
		));
	}

	/**
	 * Error 404 handler
	 */
	public function action_404()
	{
		$this->response
			->status(404)
			->json([
				'error' => __('Page not found'),
			])
		;
	}

	/**
	 * With handler for a booking object
	 * 
	 * @param Model_Booking $booking
	 * @return array
	 */
	public function with_booking(Model_Booking $booking)
	{
		// Extra data
		return [
			'items' => $this->rows($booking
				->bookedItems
				->with('item__space')
			),
			'venue' => ORM::factory('Venue')
				->join('items')->on('items.venue_id', '=', 'venue.id')
				->join('booking_items')->on('booking_items.item_id', '=', 'items.id')
				->where('booking_items.booking_id', '=', $booking->id)
				->find()
			,
		];
	}

	/**
	 * With helper
	 * 
	 * When exporting an ORM object for JSON, use the callback to manipulate the data.
	 * 
	 * @param ORM $object The source object
	 * @param callable(ORM $object):array $cb Callback to manipulate the source object, returns an assoc array with extra data
	 * @return array
	 */
	protected function with(ORM $object, $cb)
	{
		return array_merge(
			$object->jsonSerialize(),
			call_user_func($cb, $object)
		);
	}

	/**
	 * Rows helper
	 * 
	 * Transforms a ORM query into a list. Applying global filters and settings.
	 * 
	 * Supported features:
	 *  - argument $limit (int):   limit for the query
	 *  - argument $offset (int):  put an offset on the query
	 *  - argument $sort (string): apply order by on the query
	 *  - argument $count (bool):  return count instead of the results
	 * 
	 * $sort has the following syntax:
	 *   $sort=id            Sort on: id ASC
	 *   $sort=id:d          Sort on: id DESC
	 *   $sort=id:a,price:d  Sort on: id ASC, price DESC
	 * 
	 * @param ORM $query ORM query object
	 * @param callable $extraCb Extra callback to be used with the 'with' helper for every found object
	 * @param bool $count Force the $count feature
	 * @return int|Database_Result
	 */
	protected function rows(ORM $query, $extraCb = null, $count = false)
	{
		if (($limit = $this->request->query('$limit')) !== null)
		{
			$query->limit( (int) $limit);
		}

		if (($offset = $this->request->query('$offset')) !== null)
		{
			$query->offset( (int) $offset);
		}

		if (($sort = $this->request->query('$sort')) !== null)
		{
			foreach (explode(',', $sort) as $s)
			{
				$desc = false;
				if (preg_match('#^([^:]+)(a|d)?$#is', $s, $m))
				{
					$desc = strtolower(Arr::get($m, 2)) == 'd';
					$s = $m[1];
				}
				$query->order_by($s, $desc ? 'DESC': 'ASC');
			}
		}

		if ($this->request->query('$count'))
		{
			$count = true;
		}

		if ($extraCb !== null)
		{
			return $count
				? $query->count_all()
				: array_map(
					function($object) use(&$extraCb)
					{
						return $this->with($object, $extraCb);
					},
					$query
						->find_all()
						->as_array()
				)
			;
		}
		else
		{
			return $count
				? $query->count_all()
				: $query->find_all()
			;
		}
	}
}

/**
 * The routes for controller
 * 
 * The routes are accessible using a (properly setup) webserver requesting 'index.php'.
 * 
 * The routes are also testable using the CLI. This works as following:
 *  - php index.php '/path?a=b&c=d'               Similar to: GET /path?a=b&c=d
 *  - echo 'a=b&c=d' | php index.php POST /path   Similar to: POST /path  with post data 'a=b&c=d' 
 */

//////////////////////////////
// Booker routes:
//////////////////////////////

// /booker or /bookers
Route::set('bookers', 'booker(s)')->defaults([
	'controller' => 'API',
	'action' => 'bookers',
]);

// /bookers/<id> or /booker/<id>
Route::set('booker', 'booker(s)/<id>', ['id' => '\d+'])->defaults([
	'controller' => 'API',
	'action' => 'booker',
]);

//////////////////////////////
// Venue routes
//////////////////////////////

// /venue or /venues
Route::set('venues', 'venue(s)')->defaults([
	'controller' => 'API',
	'action' => 'venues',
]);

// /venues/<id> or /venue/<id>
Route::set('venue', 'venue(s)/<id>', ['id' => '\d+'])->defaults([
	'controller' => 'API',
	'action' => 'venue'
]);

//////////////////////////////
// Booking routes
//////////////////////////////

// /booking or /bookings
Route::set('bookings', 'booking(s)')->defaults([
	'controller' => 'API',
	'action' => 'bookings',
]);

// /bookings/<id> or /booking/<id>
Route::set('booking', 'booking(s)/<id>', ['id' => '\d+'])->defaults([
	'controller' => 'API',
	'action' => 'booking'
]);

//////////////////////////////
// Error routes
//////////////////////////////

// Error 404 handler
Route::set('404', '<uri>', ['uri' => '.*'])->defaults([
	'controller' => 'API',
	'action' => '404',
]);



//////////////////////////////////////////////////////////////
// Run request (handles both web requests and CLI requests)
//////////////////////////////////////////////////////////////

$request = Request::factory(Request::detect_uri(), [], false);
echo $request
	->execute()
	->send_headers()
	->body()
;
