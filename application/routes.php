<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::get('/', function()
{
	return View::make('home.index');
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

use Minaev\Services\ImagesComparer;

Route::post('/', array(function()
{
	$decoded_array = json_decode(Input::get('data'));

	$image_compare = new ImagesComparer($decoded_array);
	return json_encode($image_compare->compare());
}));

Route::get('(:num)/(:all)', array(function()
{
	$uri = URI::current();

	$arithmetic_operators = array(
		'-' => 'minus',
		'+' => 'plus',
		'*' => 'multiplication',
		'/' => 'division'
	);

	$pattern = '#([0-9]+(\/('.implode('|', $arithmetic_operators).')+\/[0-9]+)+)#';

	$matches = array();
	preg_match($pattern, $uri, $matches);

	if($matches && $matches[0] == $uri)
	{
		$search_names = array();
		$replace_symbols = array();

		foreach($arithmetic_operators as $sybmol => $operator_name)
		{
			$search_names[] = '/'.$operator_name.'/';
			$replace_symbols[] = $sybmol;
		}

		$normalized = str_ireplace(
			$search_names,
			$replace_symbols,
			$uri
		);
		return round(eval('return @('.$normalized.');'), 0, PHP_ROUND_HALF_UP);
	}

	return View::make('home.index');
}));