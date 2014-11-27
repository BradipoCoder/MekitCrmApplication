<?php
namespace Mekit\Bundle\AccountBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\AddressBundle\Entity\AbstractPhone;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity
 * @ORM\Table("mekit_account_phone", indexes={
 *      @ORM\Index(name="primary_phone_idx", columns={"phone", "is_primary"})
 * })
 * @ORM\Entity(repositoryClass="Mekit\Bundle\AccountBundle\Entity\Repository\AccountPhoneRepository")
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
class AccountPhone extends AbstractPhone {
	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", inversedBy="phones")
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $ownerAccount;

	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", inversedBy="phones")
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
}

