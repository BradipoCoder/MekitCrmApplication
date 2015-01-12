<?php
namespace Mekit\Bundle\RelationshipBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

use Mekit\Bundle\RelationshipBundle\Entity\Manager\ReferenceManager;

class DoctrineListener {
	/** @var ReferenceManager */
	protected $referenceManager;

	/**
	 * @param ReferenceManager $referenceManager
	 */
	public function __construct(ReferenceManager $referenceManager) {
		$this->referenceManager = $referenceManager;
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args) {
		$this->referenceManager->handlePrePersist($args);
	}

}