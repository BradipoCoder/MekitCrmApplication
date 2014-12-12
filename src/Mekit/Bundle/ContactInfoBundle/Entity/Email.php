<?php
namespace Mekit\Bundle\ContactInfoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\AddressBundle\Entity\AbstractEmail;
use Oro\Bundle\EmailBundle\Entity\EmailInterface;

use Mekit\Bundle\AccountBundle\Entity\Account;

/**
 * @ORM\Entity
 * @ORM\Table("mekit_email", indexes={
 *      @ORM\Index(name="primary_email_idx", columns={"email", "is_primary"})
 * })
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-envelope"
 *          },
 *          "note"={
 *              "immutable"=true
 *          },
 *          "activity"={
 *              "immutable"=true
 *          },
 *          "attachment"={
 *              "immutable"=true
 *          }
 *      }
 * )
 */
class Email extends AbstractEmail implements EmailInterface {
	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", inversedBy="emails")
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $owner_account;

	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", inversedBy="emails")
	 * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $owner_contact;

	/**
	 * @return Account
	 */
	public function getOwnerAccount() {
		return $this->owner_account;
	}

	/**
	 * @param Account $owner
	 * @return $this
	 */
	public function setOwnerAccount(Account $owner = null) {
		$this->owner_account = $owner;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isAccountEMail() {
		return (!is_null($this->owner_account));
	}

	/**
	 * @return Contact
	 */
	public function getOwnerContact() {
		return $this->owner_contact;
	}

	/**
	 * @param Contact $owner
	 * @return $this
	 */
	public function setOwnerContact(Contact $owner = null) {
		$this->owner_contact = $owner;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isContactEmail() {
		return (!is_null($this->owner_contact));
	}

	/**
	 * Returns entity which owns this Email
	 * @return Account|Contact|null
	 */
	public function getEmailOwner() {
		if ($this->isAccountEMail()) {
			return $this->getOwnerAccount();
		} else if ($this->isContactEmail()) {
			return $this->getOwnerContact();
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmailField() {
		return 'email';
	}
}