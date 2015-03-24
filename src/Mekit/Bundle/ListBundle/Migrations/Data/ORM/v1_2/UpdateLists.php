<?php
namespace Mekit\Bundle\ListBundle\Migrations\Data\ORM\v1_2;

use Mekit\Bundle\ListBundle\Migrations\Data\ORM\v1_0\UpdateLists as BaseUpdate;


class UpdateLists extends BaseUpdate {
	protected $dataFileName = '/data/v1_2/lists.yml';

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return '1.2';
	}

}