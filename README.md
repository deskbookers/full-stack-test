# Full-Stack Developer Test

Hi Full-stacker!

Great that you're interested in this exercise! Thanks a lot for making it. The exercise exists of a few assignments. They are related to the Deskbookers way of working. Good luck and we are looking forward to hearing from you soon!

To complete these assignments you need to fork this repo. When you're done you can push your changes to your own repo (and let us know where to find it ofcourse).

For this exercise it's necessary to have a working Node/NPM(/Yarn) setup and a machine with PHP (CLI) support. You can use your own web server (if you have one) or use XAMP (https://www.apachefriends.org/index.html) to install one on your own machine.

## Assignment 1: Port old PHP code to Node.JS

In this exercise we have an example database structure and some models which represend them. Included in this exercise is a SQLite3 database (and source files), PHP models and some extra PHP application code.

The task is to setup a small Node application with:
* a representation of the same models
* the application code with the same behaviour as the original PHP code

Make sure you port the following files in to a Node application:

* `assignment1/php/index.php`
* `assignment1/php/classes/Model/Booker.php`
* `assignment1/php/classes/Model/Booking.php`
* `assignment1/php/classes/Model/BookingItem.php`
* `assignment1/php/classes/Model/Item.php`
* `assignment1/php/classes/Model/Product.php`
* `assignment1/php/classes/Model/Space.php`
* `assignment1/php/classes/Model/User.php`
* `assignment1/php/classes/Model/Venue.php`
* `assignment1/php/errors.php` (if applicable)

Your resulting Node application should be placed in `assignment1/node/`. You can of course use dependencies. You can either choose to write custom SQL queries or use an ActiveRecord like library to represend your data. Make sure you pick a solution which is easily maintainable. If special software is needed to run the code make sure this is documented.

Notes:

1. Your Node application should be a standalone HTTP server.

## Assignment 2: Add a Front-End to your Node application

Now you have written a simple API in Node you are going to add an index page to the Node HTTP server. The index page should be a SPA (Single Page Application). It's recommended to use frameworks like React or Angular for this purpose.

The application should be a simple interface to view the data provided by the API. It should have at least the following features:

* Overview of bookings
  * Paginated (using $count, $limit and $offset, see code for explination)
  * The bookings should be searchable. Make them searchable on at least the following data:
    * Booking ID
    * Space name
    * Product name
    * Venue name
    * Booker name / email
* View one specific booking
  * Make sure you show all relevant data of the booking:
    * Booker
    * Space
    * Products
    * Venue
* The whole application should look nice and should be user friendly
* The application should have possibilities to navigate between the different interfaces

For the search function to work you probably need to extend the API with the search functionality.

Note the following things:
* It's not allowed to use a CSS framework (we want to see how you write CSS)
* CSS pre-processors like SASS and LESS are recommended
* Make sure your code is well structured and reusable
* Bonus: making extra additions to the API
  * API for spaces / products
  * CRUD APIs
  * Etc.
