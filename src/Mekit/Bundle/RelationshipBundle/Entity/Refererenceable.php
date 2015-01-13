<?php
namespace Mekit\Bundle\RelationshipBundle\Entity;

interface Refererenceable {

	/**
	 * Returns the ReferenceableElement associated with the entity
	 *
	 * @return ReferenceableElement
	 */
	public function getReferenceableElement();

	/**
	 * Sets the ReferenceableElement associated with the entity
	 *
	 * @param ReferenceableElement $referenceableElement
	 */
	public function setReferenceableElement(ReferenceableElement $referenceableElement);
}