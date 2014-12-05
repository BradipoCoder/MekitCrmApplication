<?php
namespace Mekit\Bundle\RelationshipBundle\Migration\Extension;

/**
 * Interface RelationshipExtensionAwareInterface
 * Should be implemented by migrations that depend on the RelationshipExtension
 */
interface RelationshipExtensionAwareInterface {
	/**
	 * Sets the Relationship
	 *
	 * @param RelationshipExtension $relationshipExtension
	 */
	public function setNoteExtension(RelationshipExtension $relationshipExtension);
}