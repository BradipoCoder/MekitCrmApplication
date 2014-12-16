<?php
namespace Mekit\Bundle\RelationshipBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;

/**
 * @ORM\Entity()
 * @ORM\Table(name="mekit_ref", indexes={
 *      @ORM\Index(name="idx_ref_type", columns={"type"})
 * }
 * )
 */
class ReferenceableElement {
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
	 * @param string $type
	 */
	public function __construct($type) {
		$this->type = $type;
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
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return Collection
	 */
	public function getReferrals() {
		return $this->referrals;
	}

	/**
	 * @param Collection $referrals
	 */
	public function setReferrals($referrals) {
		$this->referrals = $referrals;
	}

	/**
	 * @return Collection
	 */
	public function getReferences() {
		return $this->references;
	}

	/**
	 * @param Collection $references
	 */
	public function setReferences($references) {
		$this->references = $references;
	}

}