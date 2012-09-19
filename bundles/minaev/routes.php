<?php
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 19.09.12
 * Time: 12:44
 * To change this template use File | Settings | File Templates.
 */

Route::get('(:bundle)', function()
{
	return 'Minaev bundle is activated!';
});

Route::post('(:bundle)', array(function()
{
	$decoded_object = json_decode(Input::get('data'));

	if($decoded_object && is_object($decoded_object) && $decoded_object->img1 && $decoded_object->img2)
	{
		$urls_array[] = $decoded_object->img1;
		$urls_array[] = $decoded_object->img2;

		$image_compare = new \Minaev\Services\ImagesComparer($urls_array);
		return $image_compare->compare() ? 'true' : 'false';
	}

	throw new \Exception('BAD ARGS');
}));

Route::get('(:bundle)/(:num)/(:all)', array(function($first_num, $math_string)
{
	return \Minaev\Services\Calculator::parseAndCalculate($first_num.'/'.$math_string);
}));