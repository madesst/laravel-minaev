<?php namespace Minaev\Entities;
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 17.09.12
 * Time: 14:28
 * To change this template use File | Settings | File Templates.
 */

use Minaev\Repositories\ImageRepository;

class Image {

	private $url;
	private $saved = false;
	private $path;

	public function __construct($url)
	{
		$this->url = str_replace(' ', '%20', $url);
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function isSaved()
	{
		return $this->saved;
	}

	public function deleteFromDisk()
	{
		if($this->saved)
		{
			unlink($this->getPath());
		}
	}

	public function saveToDisk()
	{
		$this->path = ImageRepository::saveToDisk($this);
		if($this->path)
		{
			$this->saved = true;
		}

		return $this->saved;
	}
}