<?php namespace Minaev\Services\Validators;
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 17.09.12
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */

use Minaev\Entities\Image;
use Minaev\Entities\ImagePair;
use Minaev\Repositories\ImageRepository;

class ImageValidator {

	public static function validate(Image $image)
	{
		//Не знаю как сделать это нормально
		//filter_var самый простой способ проверки валидности урла,
		//но ему нужно указание протокола
		$url = $image->getUrl();
		if($parts = parse_url($url))
		{
			if(!isset($parts["scheme"]))
			{
				$url = "http://".$url;
			}
		}

		if(filter_var($url, FILTER_VALIDATE_URL))
		{
			if(ImageRepository::checkExistence($image))
			{
				return true;
			}
			throw new \Exception('FAILED TO FIND IMAGE AT URL: '.$image->getUrl());
		}

		throw new \Exception('INVALID IMAGE URL: '.$image->getUrl());
	}

	public static function validateImagePair(ImagePair $imagePair)
	{
		foreach($imagePair->getImagesArray() as $image)
		{
			self::validate($image);
		}

		return true;
	}

}