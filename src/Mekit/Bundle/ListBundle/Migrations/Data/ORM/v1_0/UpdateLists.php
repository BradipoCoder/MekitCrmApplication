<?php
namespace Mekit\Bundle\ListBundle\Migrations\Data\ORM\v1_0;

use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;
use Oro\Bundle\MigrationBundle\Fixture\VersionedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\Repository\ListGroupRepository;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Repository\BusinessUnitRepository;

class UpdateLists extends AbstractTranslatableEntityFixture implements VersionedFixtureInterface, ContainerAwareInterface {
	const LIST_GROUP_PREFIX = 'listgroup';
	const LIST_ITEM_PREFIX = 'listitem';

	/**
	 * @var string
	 */
	protected $dataFileName = '/data/v1_0/lists.yml';

	/**
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * @var ListGroupRepository
	 */
	protected $listGroupRepository;

	/**
	 * @var ListItemRepository
	 */
	protected $listItemRepository;

	/**
	 * @var BusinessUnit
	 */
	protected $businessUnit;

	/**
	 * @var string
	 */
	protected $locale = "en";

	/**
	 * {@inheritdoc}
	 */
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}

	public function getVersion()
	{
		return '1.0';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function loadEntities(ObjectManager $manager) {
		$fileName = $this->getFileName();
		$lists = $this->getDataFromFile($fileName);
		$this->loadLists($manager, $lists);
	}

	/**
	 * @return string
	 */
	protected function getFileName() {
		return $this->container
			->get('kernel')
			->locateResource('@MekitListBundle/Migrations/Data/ORM' . $this->dataFileName);
	}

	/**
	 * @param string $fileName
	 * @return bool
	 */
	protected function isFileAvailable($fileName) {
		return is_file($fileName) && is_readable($fileName);
	}

	/**
	 * @param string $fileName
	 * @return array
	 * @throws \LogicException
	 */
	protected function getDataFromFile($fileName) {
		if (!$this->isFileAvailable($fileName)) {
			throw new \LogicException('File ' . $fileName . 'is not available');
		}
		$fileName = realpath($fileName);
		return Yaml::parse($fileName);
	}

	/**
	 * @param ObjectManager $manager
	 * @param array $lists
	 */
	protected function loadLists(ObjectManager $manager, array $lists) {
		$this->listGroupRepository = $manager->getRepository("MekitListBundle:ListGroup");
		$this->listItemRepository = $manager->getRepository("MekitListBundle:ListItem");

		/** @var BusinessUnitRepository $businessUnitRepository */
		$businessUnitRepository = $manager->getRepository("OroOrganizationBundle:BusinessUnit");
		/** @var BusinessUnit */
		$this->businessUnit = $businessUnitRepository->find(1);


		foreach ($lists as $listGroupName => $listGroupData) {
			$listGroupData["name"] = $listGroupName;
			$listGroup = $this->getNewListGroup($listGroupData, $this->locale);
			if (!$listGroup) {
				continue;
			}
			$manager->persist($listGroup);

			if (!empty($listGroupData['items'])) {
				foreach($listGroupData['items'] as $listItemName => $listItemData) {
					$listItemData["name"] = $listItemName;
					$listItem = $this->getNewListItem($listGroup, $listItemData, $this->locale);
					if (!$listItem) {
						continue;
					}
					$manager->persist($listItem);
				}
			}
		}

		$manager->flush();
		$manager->clear();
	}

	/**
	 * @param ListGroup $listGroup
	 * @param array $listItemData
	 * @param string $locale
	 * @return null|ListItem
	 */
	protected function getNewListItem(ListGroup $listGroup, array $listItemData, $locale = "en") {
		if (empty($listItemData['name'])) {
			return null;
		}
		/** @var $listItem ListItem */
		$listItem = $this->listItemRepository->findOneBy(array('name' => $listItemData['name']));
		if(!$listItem) {

			$translationPrefix = static::LIST_ITEM_PREFIX.".".$listGroup->getName();
			$label = $this->translate($listItemData['name'], $translationPrefix, $locale);

			$cleanLabel = str_replace(" ", "_", $label);
			$cleanLabel = preg_replace("/[^a-zA-Z0-9_]+/", "", $cleanLabel);
			$name = $listGroup->getName() . "_" . strtoupper($cleanLabel);

			$listItem = new ListItem();
			$listItem
				->setListGroup($listGroup)
				->setName($name)
				->setLabel($label)
				->setDefaultItem(isset($listItemData["default_item"])?$listItemData["default_item"]:false)
				->setSystem(isset($listItemData["system"])?$listItemData["system"]:true)
				->setOwner($this->businessUnit)
				->setOrganization($this->businessUnit->getOrganization());
		}
		return($listItem);
	}

	/**
	 * @param array $listGroupData
	 * @param string $locale
	 * @return null|ListGroup
	 */
	protected function getNewListGroup(array $listGroupData, $locale = "en") {
		if (empty($listGroupData['name'])) {
			return null;
		}

		/** @var $listGroup ListGroup */
		$listGroup = $this->listGroupRepository->findOneBy(array('name' => $listGroupData['name']));
		if (!$listGroup) {
			$translationPrefix = static::LIST_GROUP_PREFIX.".".$listGroupData['name'];
			$label = $this->translate("label", $translationPrefix, $locale);
			$description = $this->translate("description", $translationPrefix, $locale);
			$emptyValue = $this->translate("empty_value", $translationPrefix, $locale);

			$listGroup = new ListGroup();
			$listGroup->setName($listGroupData['name'])
				->setLabel($label)
				->setDescription($description)
				->setEmptyValue($emptyValue)
				->setRequired($listGroupData['required'])
				->setSystem(isset($listGroupData["system"])?$listGroupData["system"]:true)
				->setOwner($this->businessUnit)
				->setOrganization($this->businessUnit->getOrganization());
		}
		return $listGroup;
	}

}