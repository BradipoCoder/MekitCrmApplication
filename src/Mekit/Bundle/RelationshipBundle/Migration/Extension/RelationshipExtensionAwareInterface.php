<?php
namespace Mekit\Bundle\RelationshipBundle\Migration\Extension;

/**
 * RelationshipExtensionAwareInterface should be implemented by migrations that depend on RelationshipExtension.
 */
interface RelationshipExtensionAwareInterface
{
    /**
     * Sets the RelationshipExtension
     *
     * @param RelationshipExtension $relationshipExtension
     */
    public function setRelationshipExtension(RelationshipExtension $relationshipExtension);
}
