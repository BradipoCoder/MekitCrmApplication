<?php
namespace Mekit\Bundle\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\AddressBundle\Entity\AbstractEmail;
use Oro\Bundle\EmailBundle\Entity\EmailInterface;

use Mekit\Bundle\ContactBundle\Entity\Contact;

/**
 * @ORM\Entity
 * @ORM\Table("mekit_account_email", indexes={
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
class AccountEmail extends AbstractEmail implements EmailInterface {
	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", inversedBy="emails")
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $ownerAccount;

	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", inversedBy="emails")
	 * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $ownerContact;

	/**
	 * @return Account
	 */
	public function getOwnerAccount() {
		return $this->ownerAccount;
	}

	/**
	 * @param Account $ownerAccount
	 * @return $this
	 */
	public function setOwnerAccount(Account $ownerAccount = null) {
		$this->ownerAccount = $ownerAccount;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isAccountEMail() {
		return (!is_null($this->ownerAccount));
	}

	/**
	 * @return Contact
	 */
	public function getOwnerContact() {
		return $this->ownerContact;
	}

	/**
	 * @param Contact $ownerContact
	 * @return $this
	 */
	public function setOwnerContact(Contact $ownerContact = null) {
		$this->ownerContact = $ownerContact;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isContactEmail() {
		return (!is_null($this->ownerContact));
	}


	/**
	 * Returns entity which owns this Email
	 * @return Account|Contact|null
	 */
	public function getEmailOwner() {
		if($this->isAccountEMail()) {
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