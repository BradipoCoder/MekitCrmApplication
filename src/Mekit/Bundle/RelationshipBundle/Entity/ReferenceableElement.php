<?php
namespace Mekit\Bundle\RelationshipBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\CallBundle\Entity\Call;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;
use Mekit\Bundle\TaskBundle\Entity\Task;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\RelationshipBundle\Entity\Repository\ReferenceableElementRepository")
 * @ORM\Table(name="mekit_ref", indexes={
 *          @ORM\Index(name="idx_ref_type", columns={"type"})
 *      }
 * )
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-sitemap"
 *          }
 *      }
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

	//-------------------------------------------ENTITY SPECIFIC INVERSE MAPPINGS (should be moved out to extend models)

	//-----------------------------------------------------------------------------------------------------------ACCOUNT
	/**
	 * @var Account
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", inversedBy="referenceableElement")
	 * @ORM\JoinColumn(onDelete="RESTRICT", nullable=true)
	 */
	protected $account;

	/**
	 * @return Account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param Account $account
	 */
	public function setAccount($account) {
		$this->account = $account;
	}

	//-----------------------------------------------------------------------------------------------------------CONTACT
	/**
	 * @var Contact
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", inversedBy="referenceableElement")
	 * @ORM\JoinColumn(onDelete="RESTRICT", nullable=true)
	 */
	protected $contact;

	/**
	 * @return Contact
	 */
	public function getContact() {
		return $this->contact;
	}

	/**
	 * @param Contact $contact
	 */
	public function setContact($contact) {
		$this->contact = $contact;
	}

	//--------------------------------------------------------------------------------------------------------------CALL
	/**
	 * @var Call
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\CallBundle\Entity\Call", inversedBy="referenceableElement")
	 * @ORM\JoinColumn(onDelete="RESTRICT", nullable=true)
	 */
	protected $call;

	/**
	 * @return Call
	 */
	public function getCall() {
		return $this->call;
	}

	/**
	 * @param Call $call
	 */
	public function setCall($call) {
		$this->call = $call;
	}

	//------------------------------------------------------------------------------------------------------------MEETING
	/**
	 * @var Meeting
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\MeetingBundle\Entity\Meeting", inversedBy="referenceableElement")
	 * @ORM\JoinColumn(onDelete="RESTRICT", nullable=true)
	 */
	protected $meeting;

	/**
	 * @return Meeting
	 */
	public function getMeeting() {
		return $this->meeting;
	}

	/**
	 * @param Meeting $meeting
	 */
	public function setMeeting($meeting) {
		$this->meeting = $meeting;
	}

	//--------------------------------------------------------------------------------------------------------------TASK
	/**
	 * @var Task
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\TaskBundle\Entity\Task", inversedBy="referenceableElement")
	 * @ORM\JoinColumn(onDelete="RESTRICT", nullable=true)
	 */
	protected $task;

	/**
	 * @return Task
	 */
	public function getTask() {
		return $this->task;
	}

	/**
	 * @param Task $task
	 */
	public function setTask($task) {
		$this->task = $task;
	}



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

	/**
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getId()."-".$this->getType();
	}
}