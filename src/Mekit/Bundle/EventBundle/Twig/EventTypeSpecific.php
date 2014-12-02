<?php
namespace Mekit\Bundle\EventBundle\Twig;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
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

	/**
	 * @return array
	 */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('mkt_ev_type_spec_cfg', array($this, 'getEntityConfig')),
			new \Twig_SimpleFunction('mkt_ev_type_spec_link', array($this, 'getEntityLink')),
			new \Twig_SimpleFunction('mkt_ev_type_spec_dump', array($this, 'dumpEntityConfig'))
		);
	}

	/**
	 * @param string $entityName
	 * @param string $routeName
	 */
	public function getEntityLink($entityName, $routeName) {
		$configManager = $this->configProvider->getConfigManager();
		$metadata = $configManager->getEntityMetadata($entityName);
		if ($metadata && $metadata->defaultValues && isset($metadata->defaultValues["eventable"])) {
			$eventableDefaultValues = $metadata->defaultValues["eventable"];
			if(isset($eventableDefaultValues[$routeName])) {
				$routeName = $eventableDefaultValues[$routeName];
				return($routeName);
			} else {
				throw new \LogicException(sprintf("This entity(%s) does not have this route name(%s) defined!", $entityName, $routeName));
			}
		} else {
			throw new \LogicException(sprintf("This entity(%s) does not have 'eventable' configurations!", $entityName));
		}
	}

	/**
	 * Dumps "eventable" entity configuration
	 * @param string $entityName
	 * @return string
	 */
	public function dumpEntityConfig($entityName) {
		$configManager = $this->configProvider->getConfigManager();
		$metadata = $configManager->getEntityMetadata($entityName);
		if ($metadata && $metadata->defaultValues && isset($metadata->defaultValues["eventable"])) {
			var_dump ($metadata->defaultValues["eventable"]);
		} else {
			throw new \LogicException(sprintf("This entity(%s) does not have 'eventable' configurations!", $entityName));
		}
	}

	/**
	 * @param string $entityName
	 * @return ConfigInterface
	 */
	public function getEntityConfig($entityName) {
		return($this->configProvider->getConfig($entityName));
	}


	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_event_type_specific';
	}
}