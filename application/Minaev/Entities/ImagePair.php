<?php namespace Minaev\Entities;
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 17.09.12
 * Time: 15:28
 * To change this template use File | Settings | File Templates.
 */

class ImagePair {

	private $image_left;
	private $image_right;

	public function __construct(Image $image_left, Image $image_right)
	{
		$this->image_left = $image_left;
		$this->image_right = $image_right;
	}

	public function saveFromUrl()
	{
		foreach($this->getImagesArray() as $image)
		{
			if(!$image->save())
			{
				return false;
			}
		}

		return true;
	}

	public function getImagesArray()
	{
		return array($this->image_left, $this->image_right);
	}

	public function getImagesPathsArray()
	{
		$return_array = array();

		foreach($this->getImagesArray() as $image)
		{
			$return_array[] = $image->getPath();
		}

		return $return_array;
	}
}