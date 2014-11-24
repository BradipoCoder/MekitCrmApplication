<?php
namespace Mekit\Bundle\ListBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\ListItem;

class LoadListAccountType extends AbstractFixture {
	private $listGroupData = [
		'name' => 'ACCOUNT_TYPE',
		'label' => 'Type',
		'description' => 'Account types'
	];
	/**
	 * @var array
	 */
	protected $listGroupItems = [
		['id'=>'ACCT_CLNT', 'label'=>'Client'],
		['id'=>'ACCT_SPLR', 'label'=>'Supplier'],
		['id'=>'ACCT_CPTR', 'label'=>'Competitor'],
		['id'=>'ACCT_OTHR', 'label'=>'Other']
	];

	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager) {
		$listGroup = new ListGroup();
		$listGroup->setName($this->listGroupData["name"])
			->setLabel($this->listGroupData["label"])
			->setDescription($this->listGroupData["description"]);
		$manager->persist($listGroup);

		foreach ($this->listGroupItems as $itemData) {
			$listItem = new ListItem();
			$listItem->setId($itemData["id"])->setLabel($itemData["label"]);
			$listGroup->addItem($listItem);
		}

		$manager->flush();
	}
}