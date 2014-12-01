<?php
namespace Mekit\Bundle\EventBundle\Twig;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;

/**
 * Class EventTypeSpecific
 */
class EventTypeSpecific  extends \Twig_Extension {
	/**
	 * @var ConfigProvider
	 */
	protected $configProvider;

	/**
	 * @var ObjectManager
	 */
	protected $manager;

	/**
	 * @param ConfigProvider $configProvider
	 * @param ObjectManager  $manager
	 */
	public function __construct(ConfigProvider $configProvider, ObjectManager $manager) {
		$this->configProvider = $configProvider;
		$this->manager = $manager;
	}

	/*
	 * from: Oro\Bundle\EntityConfigBundle\Controller\ConfigController
	 *
	 * /* * @var ConfigProvider $entityConfigProvider * /
	 * $entityConfigProvider = $this->get('oro_entity_config.provider.entity');
	 * 'entity_config'     => $entityConfigProvider->getConfig($entity->getClassName()),
	 * */

	/**
	 * @return array
	 */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('mkt_ev_type_spec_icon', array($this, 'getTypeIcon')),
		);
	}

	/**
	 * Returns icon name for entity
	 * @param string $entityName
	 * @return string
	 */
	public function getTypeIcon($entityName) {
		/** @var ConfigInterface $entityConfig */
		$entityConfig = $this->configProvider->getConfig($entityName);
		return $entityConfig->get("icon");
	}



	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_event_type_specific';
	}
}