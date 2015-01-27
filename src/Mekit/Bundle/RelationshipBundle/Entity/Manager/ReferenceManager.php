<?php
namespace Mekit\Bundle\RelationshipBundle\Entity\Manager;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Doctrine\ORM\EntityManager;

class ReferenceManager {
	/** @var ConfigManager */
	protected $configManager;

	/** @var  EntityManager */
	protected $entityManager;

	/**
	 * @param ConfigManager $configManager
	 * @param EntityManager $entityManager
	 */
	public function __construct(ConfigManager $configManager, EntityManager $entityManager) {
		$this->configManager = $configManager;
		$this->entityManager = $entityManager;
	}

	/**
	 * Returns array of entity configs which are configured as "referenceable"
	 * todo: pass referenceableElement and check if "can_reference_itself" and if not exclude itself from list
	 * @return array|ConfigInterface[]
	 */
	public function getReferenceableEntityConfigurations() {
		$list = [];
		$configs = $this->configManager->getConfigs("relationship");
		/** @var ConfigInterface $config */
		foreach($configs as $config) {
			if($config->get("referenceable") === true) {
				$list[] = $config;
			}
		}
		return $list;
	}

	/**
	 * @param string $className
	 * @return Boolean|ConfigInterface
	 */
	public function getRelationshipConfiguration($className) {
		$classConfig = false;
		if($this->configManager->hasConfig($className)) {
			$configId = $this->configManager->getId("relationship", $className);
			$classConfig = $this->configManager->getConfig($configId);
		}
		return $classConfig;
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * Returns real class name for an object
	 * If proxy object is given it will return its 'real'/'original' class name
	 * @param mixed $object
	 * @return string
	 */
	public function getRealClassName($object) {
		return $this->entityManager->getMetadataFactory()->getMetadataFor(get_class($object))->getName();
	}
}