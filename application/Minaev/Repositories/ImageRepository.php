<?php namespace Minaev\Repositories;
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 17.09.12
 * Time: 14:30
 * To change this template use File | Settings | File Templates.
 */

use Minaev\Entities\Image;

class ImageRepository {

	const CURL_TIMEOUT = 10;
	public static function saveToDisk(Image $image)
	{
		$c = curl_init();
		$file_name = path('storage').'cache/'.md5(time().microtime(true).md5($image->getUrl()).rand());
		$fd = fopen($file_name, 'w');

		$c = self::prepareCurlForUrl($image->getUrl());
		curl_setopt($c, CURLOPT_FILE, $fd);

		$result = curl_exec($c);
		fclose($fd);
		curl_close($c);

		return $result ? $file_name : false;
	}

	public static function checkExistence(Image $image)
	{
		$c = self::prepareCurlForUrl($image->getUrl());
		curl_setopt($c, CURLOPT_HEADER, 1);
		curl_setopt($c, CURLOPT_NOBODY, 1);

		$result = curl_exec($c);
		//Можно добавить проверку mime type
		curl_close($c);

		return $result;
	}

	protected static function prepareCurlForUrl($url)
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

		return $c;
	}

}