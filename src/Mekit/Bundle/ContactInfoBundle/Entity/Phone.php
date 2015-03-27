<?php
namespace Mekit\Bundle\ContactInfoBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\AddressBundle\Entity\AbstractPhone;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity
 * @ORM\Table("mekit_phone", indexes={
 *      @ORM\Index(name="primary_phone_idx", columns={"phone", "is_primary"})
 * })
 * @ORM\Entity(repositoryClass="Mekit\Bundle\ContactInfoBundle\Entity\Repository\PhoneRepository")
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-phone"
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
class Phone extends AbstractPhone
{
	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", inversedBy="phones")
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $owner_account;

	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", inversedBy="phones")
	 * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $owner_contact;

	/**
	 * Returns entity which owns this Phone
	 * @return Account|Contact|null
	 */
	public function getEmailOwner() {
		if ($this->isAccountPhone()) {
			return $this->getOwnerAccount();
		} else if ($this->isContactPhone()) {
			return $this->getOwnerContact();
		}

		return null;
	}

	/**
	 * @return bool
	 */
	public function isAccountPhone() {
		return (!is_null($this->owner_account));
	}

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
	public function isContactPhone() {
		return (!is_null($this->owner_contact));
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
}

