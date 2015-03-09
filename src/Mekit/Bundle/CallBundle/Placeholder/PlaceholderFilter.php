<?php
namespace Mekit\Bundle\CallBundle\Placeholder;

use Mekit\Bundle\CrmBundle\Relationship\RelationshipManager;

class PlaceholderFilter
{
	CONST ENTITY_CALL = 'Mekit\Bundle\CallBundle\Entity\Call';

	/** @var  RelationshipManager */
	protected $relationshipManager;

	/**
	 * @param RelationshipManager $relationshipManager
	 */
	public function __construct(RelationshipManager $relationshipManager) {
		$this->relationshipManager = $relationshipManager;
	}

	/**
	 * Checks if the entity has associations to Call entity
	 *
	 * @param object $entity
	 * @return bool
	 */
	public function isCallAssociationEnabled($entity) {
		return $this->relationshipManager->entityHasCollectionAssociationTo($entity, self::ENTITY_CALL);
	}
}