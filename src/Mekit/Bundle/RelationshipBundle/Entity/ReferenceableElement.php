<?php
namespace Mekit\Bundle\RelationshipBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\FormBundle\Entity\EmptyItem;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\RelationshipBundle\Entity\Repository\ReferenceableElementRepository")
 * @ORM\Table(name="mekit_ref", indexes={
 *      @ORM\Index(name="idx_ref_type", columns={"type"})
 * }
 * )
 */
class ReferenceableElement implements EmptyItem {//
	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="type", length=255, nullable=false)
	 */
	protected $type;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="tmp", length=255, nullable=true)
	 */
	protected $tmp;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="ReferenceableElement", mappedBy="references")
	 */
	protected $referrals;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="ReferenceableElement", inversedBy="referrals")
	 * @ORM\JoinTable(name="mekit_ref_refs",
	 *      joinColumns={@ORM\JoinColumn(name="referral_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="referenced_id", referencedColumnName="id")}
	 * )
	 */
	protected $references;

	/**
	 */
	public function __construct() {
		$this->referrals = new ArrayCollection();
		$this->references = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTmp() {
		return $this->tmp;
	}

	/**
	 * @param string $tmp
	 */
	public function setTmp($tmp) {
		$this->tmp = $tmp;
	}



	/**
	 * @return Collection|ReferenceableElement[]
	 */
	public function getReferences() {
		return $this->references;
	}

	/**
	 * @param Collection|ReferenceableElement[] $references
	 * @return $this
	 */
	public function setReferences($references) {
		$this->references->clear();
		foreach($references as $reference) {
			$this->addReference($reference);
		}
		return $this;
	}

	/**
	 * @param ReferenceableElement $reference
	 * @return $this
	 */
	public function addReference(ReferenceableElement $reference) {
		if (!$this->references->contains($reference)) {
			$this->references->add($reference);
			$reference->addReferral($this);
		}
		return $this;
	}

	/**
	 * @param ReferenceableElement $reference
	 * @return $this
	 */
	public function removeReference(ReferenceableElement $reference) {
		if ($this->references->contains($reference)) {
			$this->references->removeElement($reference);
		}
		return $this;
	}

	/**
	 * @param ReferenceableElement $reference
	 * @return boolean
	 */
	public function hasReference(ReferenceableElement $reference) {
		return $this->getReferences()->contains($reference);
	}

	/**
	 * @return Collection|ReferenceableElement[]
	 */
	public function getReferrals() {
		return $this->referrals;
	}

	/**
	 * @param Collection|ReferenceableElement[] $referrals
	 * @return $this
	 */
	public function setReferrals($referrals) {
		$this->referrals->clear();
		foreach($referrals as $referral) {
			$this->addReferral($referral);
		}
		return $this;
	}

	/**
	 * @param ReferenceableElement $referral
	 * @return $this
	 */
	public function addReferral(ReferenceableElement $referral) {
		if (!$this->referrals->contains($referral)) {
			$this->referrals->add($referral);
			$referral->addReference($this);
		}
		return $this;
	}

	/**
	 * @param ReferenceableElement $referral
	 * @return $this
	 */
	public function removeReferral(ReferenceableElement $referral) {
		if ($this->referrals->contains($referral)) {
			$this->referrals->removeElement($referral);
		}
		return $this;
	}

	/**
	 * @param ReferenceableElement $referral
	 * @return boolean
	 */
	public function hasReferral(ReferenceableElement $referral) {
		return $this->getReferrals()->contains($referral);
	}

	public function isEmpty() {
		return($this->id === null);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getId()."-".$this->getType();
	}
}