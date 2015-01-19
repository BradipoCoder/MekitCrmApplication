<?php
namespace Mekit\Bundle\RelationshipBundle\Entity\Manager;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;

class ReferenceManager {
	/** @var ConfigManager */
	protected $configManager;

	/**
	 * @param ConfigManager $configManager
	 */
	public function __construct(ConfigManager $configManager) {
		$this->configManager = $configManager;
	}

	/**
	 * Handle prePersist event - called by DoctrineListener
	 *
	 * When registering e new entity configured as "referenceable" it will create
	 * the corresponding ReferenceableElement for it
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function handlePrePersist(LifecycleEventArgs $args){
		$entity = $args->getEntity();
		$className = get_class($entity);
		$classConfig = $this->getRelationshipConfiguration($className);
		if($classConfig && $classConfig->get("referenceable") === true) {
			$referenceableElement = new ReferenceableElement();
			$entity->setReferenceableElement($referenceableElement);
		}
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
}