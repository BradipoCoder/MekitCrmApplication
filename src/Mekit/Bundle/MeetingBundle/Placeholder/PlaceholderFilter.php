<?php
namespace Mekit\Bundle\MeetingBundle\Placeholder;

use Mekit\Bundle\CrmBundle\Relationship\RelationshipManager;

class PlaceholderFilter
{
	CONST ENTITY_MEETING = 'Mekit\Bundle\MeetingBundle\Entity\Meeting';

	/** @var  RelationshipManager */
	protected $relationshipManager;

	/**
	 * @param RelationshipManager $relationshipManager
	 */
	public function __construct(RelationshipManager $relationshipManager) {
		$this->relationshipManager = $relationshipManager;
	}

	/**
	 * Checks if the entity has associations to Meeting entity
	 *
	 * @param object $entity
	 * @return bool
	 */
	public function isMeetingAssociationEnabled($entity) {
		return $this->relationshipManager->entityHasCollectionAssociationTo($entity, self::ENTITY_MEETING);
	}
}