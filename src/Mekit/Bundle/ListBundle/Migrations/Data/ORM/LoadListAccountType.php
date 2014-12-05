<?php
namespace Mekit\Bundle\ListBundle\Migrations\Data\ORM;

use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;
use Oro\Bundle\MigrationBundle\Fixture\VersionedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\Repository\ListGroupRepository;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;

class LoadListAccountType extends AbstractTranslatableEntityFixture implements VersionedFixtureInterface, ContainerAwareInterface {
	const LIST_GROUP_PREFIX = 'listgroup';
	const LIST_ITEM_PREFIX = 'listitem';

	/**
	 * @var string
	 */
	protected $dataFileName = '/data/lists.yml';

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
	 * {@inheritdoc}
	 */
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
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
		$translationLocales = $this->getTranslationLocales();


		foreach ($translationLocales as $locale) {
			foreach ($lists as $listGroupName => $listGroupData) {
				$listGroupData["name"] = $listGroupName;
				$listGroup = $this->getListGroup($locale, $listGroupData);
				if (!$listGroup) {
					continue;
				}
				$manager->persist($listGroup);

				if (!empty($listGroupData['items'])) {
					foreach($listGroupData['items'] as $listItemId => $listItemData) {
						$listItemData["id"] = $listItemId;
						$listItem = $this->getListItem($locale, $listGroup, $listItemData);
						if (!$listItem) {
							continue;
						}
						$manager->persist($listItem);
					}
				}
			}
		}
		$manager->flush();
		$manager->clear();
	}

	/**
	 * @param string $locale
	 * @param ListGroup $listGroup
	 * @param array $listItemData
	 * @return null|ListItem
	 */
	protected function getListItem($locale, ListGroup $listGroup, array $listItemData) {
		if (empty($listItemData['id'])) {
			return null;
		}
		/** @var $listItem ListItem */
		$listItem = $this->listItemRepository->find($listItemData["id"]);
		if(!$listItem) {

			$translationPrefix = static::LIST_ITEM_PREFIX;
			$label = $this->translate($listItemData['id'], $translationPrefix, $locale);

			$listItem = new ListItem();
			$listItem->setId($listItemData["id"])
				->setListGroup($listGroup)
				->setLabel($label)
				->setDefaultItem(isset($listItemData["default_item"])?$listItemData["default_item"]:false)
				->setSystem(isset($listItemData["system"])?$listItemData["system"]:true);
		}
		return($listItem);
	}

	/**
	 * @param string $locale
	 * @param array $listGroupData
	 * @return null|ListGroup
	 */
	protected function getListGroup($locale, array $listGroupData) {
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
				->setItemPrefix($listGroupData['item_prefix'])
				->setRequired($listGroupData['required'])
				->setSystem($listGroupData['system']);
		}
		return $listGroup;
	}

}