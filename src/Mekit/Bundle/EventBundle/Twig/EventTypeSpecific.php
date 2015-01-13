<?php
namespace Mekit\Bundle\EventBundle\Twig;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;

/**
 * Class EventTypeSpecific
 */
class EventTypeSpecific extends \Twig_Extension {
	/**
	 * @var ConfigProvider
	 */
	protected $configProvider;

	/**
	 * @param ConfigProvider $configProvider (oro_entity_config.provider.mekitevent)

	 */
	public function __construct(ConfigProvider $configProvider) {
		$this->configProvider = $configProvider;
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
	 * @return string
	 */
	public function getEntityLink($entityName, $routeName) {
		$config = $this->getEntityConfig($entityName);
		$routeName = $config->get($routeName);
		if(!$routeName) {
			throw new \LogicException(sprintf("This entity(%s) does not have this route name(%s) defined!", $entityName, $routeName));
		}
		return($routeName);
	}

	/**
	 * Dumps "eventable" entity configuration
	 * @param string $entityName
	 * @return string
	 */
	public function dumpEntityConfig($entityName) {
		$config = $this->getEntityConfig($entityName);
		var_dump($config);
	}

	/**
	 * @param string $entityName
	 * @return ConfigInterface
	 */
	public function getEntityConfig($entityName) {
		$config = $this->configProvider->getConfig($entityName);
		if(!$config) {
			throw new \LogicException(sprintf("This entity(%s) does not have configuration scope 'mekitevent'!", $entityName));
		}
		if($config->get("eventable") !== true) {
			throw new \LogicException(sprintf("This entity(%s) is not configured as 'eventable' under configuration scope 'mekitevent'!", $entityName));
		}
		return($config);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_event_type_specific';
	}
}