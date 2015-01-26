<?php
namespace Mekit\Bundle\RelationshipBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;


class DoctrineListener {
	/** @var ConfigManager */
	protected $configManager;

	/**
	 * @param ConfigManager $configManager
	 */
	public function __construct(ConfigManager $configManager) {
		$this->configManager = $configManager;
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args) {
		$this->handlePrePersist($args);
	}

	/**
	 * Handle prePersist event - called by DoctrineListener
	 *
	 * When registering e new entity configured as "referenceable" it will create
	 * the corresponding ReferenceableElement for it
	 *
	 * @param LifecycleEventArgs $args
	 */
	protected function handlePrePersist(LifecycleEventArgs $args){
		$entity = $args->getEntity();
		$className = get_class($entity);
		$classConfig = $this->getRelationshipConfiguration($className);
		if($classConfig && $classConfig->get("referenceable") === true) {
			$referenceableElement = new ReferenceableElement();
			$entity->setReferenceableElement($referenceableElement);
		}
	}

	/**
	 * @note: this is a copy of method from class ReferenceManager which cannot be injected into this service because it
	 * uses Doctrine\ORM\EntityManager as injected service which would create a circular reference error
	 * @param string $className
	 * @return Boolean|ConfigInterface
	 */
	private function getRelationshipConfiguration($className) {
		$classConfig = false;
		if($this->configManager->hasConfig($className)) {
			$configId = $this->configManager->getId("relationship", $className);
			$classConfig = $this->configManager->getConfig($configId);
		}
		return $classConfig;
	}

}