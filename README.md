# Full-Stack Developer Test

Hi Full-stacker!

Great that you're interested in this exercise! Thanks a lot for making it. The exercise exists of a few assignments. They are related to the Deskbookers way of working. Good luck and we are looking forward to hearing from you soon!

To complete these assignments you need to fork this repo. When you're done you can push your changes to your own repo (and let us know where to find it ofcourse).

For this exercise it's necessary to have a working Node/NPM setup and a machine with PHP (CLI) support. You can use your own web server (if you have one) or use XAMP (https://www.apachefriends.org/index.html) to install one on your own machine.

## Assignment 1: Port old PHP code to Node.JS

In this exercise we have an example database structure and some models which represend them. Included in this exercise is a SQLite3 database (and source files), PHP models and some extra PHP application code.

The task is to setup a small Node application with:
* a representation of the same models
* the application code with the same behaviour as the original PHP code

Make sure you port the following files in to a Node application:

* `assignment1/php/index.php`
* `assignment1/php/models/Booker.php`
* `assignment1/php/models/Booking.php`
* `assignment1/php/models/BookingItem.php`
* `assignment1/php/models/Item.php`
* `assignment1/php/models/Product.php`
* `assignment1/php/models/Space.php`
* `assignment1/php/models/User.php`
* `assignment1/php/models/Venue.php`
* `assignment1/php/errors.php` (if applicable)

Your resulting Node application should be placed in `assignment1/node/`. You can of course use dependencies. You can either choose to write custom SQL queries or use an ActiveRecord like library to represend your data. Make sure you pick a solution which is easily maintainable.
