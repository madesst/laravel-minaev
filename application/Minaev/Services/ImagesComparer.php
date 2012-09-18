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
		$this->imagePair = new ImagePair(
			new Image(array_pop($images_array)),
			new Image(array_pop($images_array))
		);

		ImageValidator::validateImagePair($this->imagePair);
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