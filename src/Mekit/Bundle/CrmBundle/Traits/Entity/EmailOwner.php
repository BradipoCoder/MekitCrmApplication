<?php
namespace Mekit\Bundle\CrmBundle\Traits\Entity;

use Doctrine\Common\Collections\Collection;
use Mekit\Bundle\ContactInfoBundle\Entity\Email;


trait EmailOwner {
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

	/**
	 * Get names of fields contain email addresses
	 *
	 * @return string[]|null
	 */
	public function getEmailFields() {
		return null;
	}
}