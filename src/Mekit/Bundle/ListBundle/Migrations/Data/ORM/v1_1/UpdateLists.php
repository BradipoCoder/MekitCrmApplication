<?php
namespace Mekit\Bundle\ListBundle\Migrations\Data\ORM\v1_1;

use Mekit\Bundle\ListBundle\Migrations\Data\ORM\v1_0\UpdateLists as BasUpdate;

class UpdateLists extends BasUpdate
{
	/**
	 * @var string
	 */
	protected $dataFileName = '/data/v1_1/lists.yml';

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return '1.1';
	}

}