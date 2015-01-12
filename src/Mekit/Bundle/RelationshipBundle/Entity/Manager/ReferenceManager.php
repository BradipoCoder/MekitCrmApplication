<?php
namespace Mekit\Bundle\RelationshipBundle\Entity\Manager;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;

class ReferenceManager {
	/** @var ConfigProvider */
	protected $relationshipConfigProvider;

	/**
	 * @param ConfigProvider $relationshipConfigProvider
	 */
	public function __construct(ConfigProvider $relationshipConfigProvider) {
		$this->relationshipConfigProvider = $relationshipConfigProvider;
	}

	/**
	 * Handle prePersist event
	 *
	 * When registering e new entity configured as "referenceable" it will create
	 * the corresponding ReferenceableElement for it
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function handlePrePersist(LifecycleEventArgs $args){
		$entity = $args->getEntity();
		$className = get_class($entity);
		$classConfig = $this->getReferenceableConfig($className);
		if($classConfig && $classConfig->get("referenceable") === true) {
			$referenceableElement = new ReferenceableElement();
			$referenceableElement->setType($className);
			$entity->setReferenceableElement($referenceableElement);
		}
	}


	/**
	 * @param string $className
	 * @return bool|ConfigInterface
	 */
	public function getReferenceableConfig($className) {
		$answer = false;
		if($this->relationshipConfigProvider->hasConfig($className)) {
			$answer = $this->relationshipConfigProvider->getConfig($className);
		}
		return $answer;
	}

}