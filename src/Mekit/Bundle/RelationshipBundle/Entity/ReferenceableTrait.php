<?php
namespace Mekit\Bundle\RelationshipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ReferenceableTrait {
	/**
	 * @var ReferenceableElement
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement", cascade={"persist"}, orphanRemoval=true)
	 * @ORM\JoinColumn(name="rid", referencedColumnName="id", onDelete="RESTRICT", nullable=false)
	 */
	protected $referenceableElement;

	/**
	 * @return ReferenceableElement
	 */
	public function getReferenceableElement() {
		return $this->referenceableElement;
	}

	/**
	 * @param ReferenceableElement $referenceableElement
	 */
	public function setReferenceableElement(ReferenceableElement $referenceableElement) {
		$this->referenceableElement = $referenceableElement;
	}

}