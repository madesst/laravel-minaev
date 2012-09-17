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
use Minaev\Services\Validators\ImageValidator;

class ImagesComparer
{
	const MAX_DIFF = 30;
	private $imagePair;

	public function __construct(array $images_array)
	{
//		if(!is_array($images_array) || !count($images_array) == 2)
//		{
//			throw new \Exception('NEED 2 URLs');
//		}

		$this->imagePair = new ImagePair(
			new Image(array_pop($images_array)),
			new Image(array_pop($images_array))
		);

		ImageValidator::validateImagePair($this->imagePair);
	}

	public function compare()
	{
		$this->imagePair->saveFromUrl();
		$comparer = new ImagesComparerLogic($this->imagePair->getImagesPathsArray());
		$comparer->Compare();

		foreach($this->imagePair->getImagesArray() as $image)
		{
			$image->deleteFile();
		}

		return $comparer->Images[0][1] >= self::MAX_DIFF ? false : true;
	}
}