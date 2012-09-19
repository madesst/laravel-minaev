<?php namespace Minaev\Services;
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 17.09.12
 * Time: 15:26
 * To change this template use File | Settings | File Templates.
 */

use Minaev\Entities\Image;
use Minaev\Entities\ImagePair;
use Laravel\Validator;

class ImagesComparer
{
	const MAX_DIFF = 30;
	private $imagePair;

	public function __construct(array $images_array)
	{
		$this->imagePair = new ImagePair(
			new Image(array_pop($images_array)),
			new Image(array_pop($images_array))
		);

		foreach($this->imagePair->getImagesArray() as $image)
		{
			$rules = array(
				'url'  => 'required|image_url',
			);

			$values = array('url' => $image->getUrl());

			$validation = Validator::make($values, $rules);
			if($validation->fails())
			{
				throw new \Exception(implode(';', $values).' - '.implode(';', $validation->errors->all()));
			}
		}
	}

	public function compare()
	{
		$this->imagePair->saveToDisk();

		$comparer = new ImagesComparerLogic($this->imagePair->getImagesPathsArray());
		$comparer->Compare();

		$this->imagePair->deleteFromDisk();

		return $comparer->Images[0][1][1] >= self::MAX_DIFF ? false : true;
	}
}

//Не очень понял куда нужно это класть это по фен-шую
Validator::register('image_url', function($attribute, $value, $parameters)
{
	$url = $value;
	if($parts = parse_url($url))
	{
		if(!isset($parts["scheme"]))
		{
			$url = "http://".$url;
		}
	}

	if(filter_var($url, FILTER_VALIDATE_URL))
	{
		if(\Minaev\Repositories\ImageRepository::checkExistence(new Image($url)))
		{
			return true;
		}
	}

	return false;
});