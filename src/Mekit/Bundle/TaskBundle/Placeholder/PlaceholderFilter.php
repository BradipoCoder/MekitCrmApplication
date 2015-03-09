<?php
namespace Mekit\Bundle\TaskBundle\Placeholder;

use Mekit\Bundle\CrmBundle\Relationship\RelationshipManager;

class PlaceholderFilter
{
	CONST ENTITY_TASK = 'Mekit\Bundle\TaskBundle\Entity\Task';

	/** @var  RelationshipManager */
	protected $relationshipManager;

	/**
	 * @param RelationshipManager $relationshipManager
	 */
	public function __construct(RelationshipManager $relationshipManager) {
		$this->relationshipManager = $relationshipManager;
	}

	/**
	 * Checks if the entity has associations to Task entity
	 *
	 * @param object $entity
	 * @return bool
	 */
	public function isTaskAssociationEnabled($entity) {
		return $this->relationshipManager->entityHasCollectionAssociationTo($entity, self::ENTITY_TASK);
	}
}