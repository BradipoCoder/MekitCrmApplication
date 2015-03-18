<?php
namespace Mekit\Bundle\AccountBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\ContactInfoBundle\Entity\Email;

/**
 * @ORM\MappedSuperclass
 */
class EmailOwner extends Phones {
	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactInfoBundle\Entity\Email", mappedBy="owner_account", cascade={"all"})
	 * @ORM\OrderBy({"primary" = "DESC"})
	 * @Soap\ComplexType("Mekit\Bundle\ContactInfoBundle\Entity\Email[]", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=210
	 *          }
	 *      }
	 * )
	 */
	protected $emails;

	public function __construct() {
		parent::__construct();
		$this->emails = new ArrayCollection();
	}

	/**
	 * Set emails
	 *
	 * @param Collection|Email[] $emails
	 * @return $this
	 */
	public function resetEmails($emails) {
		$this->emails->clear();
		foreach ($emails as $email) {
			$this->addEmail($email);
		}
		return $this;
	}

	/**
	 * Add email
	 *
	 * @param Email $email
	 * @return $this
	 */
	public function addEmail(Email $email) {
		if (!$this->emails->contains($email)) {
			$this->emails->add($email);
			$email->setOwnerAccount($this);
		}
		return $this;
	}

	/**
	 * Remove email
	 *
	 * @param Email $email
	 * @return $this
	 */
	public function removeEmail(Email $email) {
		if ($this->emails->contains($email)) {
			$this->emails->removeElement($email);
		}
		return $this;
	}

	/**
	 * Get emails
	 *
	 * @return Collection|Email[]
	 */
	public function getEmails() {
		return $this->emails;
	}

	/**
	 * @return null|string
	 */
	public function getEmail() {
		$primaryEmail = $this->getPrimaryEmail();
		if (!$primaryEmail) {
			return null;
		}
		return $primaryEmail->getEmail();
	}

	/**
	 * @param Email $email
	 * @return bool
	 */
	public function hasEmail(Email $email) {
		return $this->getEmails()->contains($email);
	}

	/**
	 * Gets primary email if it's available.
	 *
	 * @return Email|null
	 */
	public function getPrimaryEmail() {
		$result = null;
		foreach ($this->getEmails() as $email) {
			if ($email->isPrimary()) {
				$result = $email;
				break;
			}
		}
		return $result;
	}

	/**
	 * @param Email $email
	 * @return $this
	 */
	public function setPrimaryEmail(Email $email) {
		if ($this->hasEmail($email)) {
			$email->setPrimary(true);
			foreach ($this->getEmails() as $otherEmail) {
				if (!$email->isEqual($otherEmail)) {
					$otherEmail->setPrimary(false);
				}
			}
		}
		return $this;
	}
}