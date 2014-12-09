<?php
namespace Mekit\Bundle\ContactInfoBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\FormBundle\Entity\PrimaryItem;

/**
 * @ORM\Entity
 * @ORM\Table("mekit_address")
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *       defaultValues={
 *          "entity"={
 *              "icon"="icon-map-marker"
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
class Address extends AbstractAddress implements PrimaryItem {
	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", inversedBy="addresses", cascade={"persist"})
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $owner_account;

	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", inversedBy="addresses", cascade={"persist"})
	 * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $owner_contact;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_primary", type="boolean", nullable=true)
	 * @Soap\ComplexType("boolean", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $primary;

	public function __construct() {
		$this->primary = false;
	}

	/**
	 * Set Account as owner.
	 *
	 * @param Account $owner
	 * @return $this
	 */
	public function setOwnerAccount(Account $owner = null) {
		$this->owner_account = $owner;
		return $this;
	}

	/**
	 * Get owner account.
	 *
	 * @return Account
	 */
	public function getOwnerAccount() {
		return $this->owner_account;
	}

	/**
	 * @return bool
	 */
	public function isAccountAddress() {
		return (!is_null($this->owner_account));
	}

	/**
	 * Set Contact as owner.
	 *
	 * @param Contact $owner
	 * @return $this
	 */
	public function setOwnerContact(Contact $owner = null) {
		$this->owner_contact = $owner;
		return $this;
	}

	/**
	 * Get owner contact.
	 *
	 * @return Contact
	 */
	public function getOwnerContact() {
		return $this->owner_contact;
	}

	/**
	 * @return bool
	 */
	public function isContactAddress() {
		return (!is_null($this->owner_contact));
	}


	/**
	 * Returns entity which owns this Address
	 * @return Account|Contact|null
	 */
	public function getAddressOwner() {
		if ($this->isAccountAddress()) {
			return $this->getOwnerAccount();
		} else if ($this->isContactAddress()) {
			return $this->getOwnerContact();
		}
		return null;
	}

	/**
	 * @param bool $primary
	 * @return $this
	 */
	public function setPrimary($primary) {
		$this->primary = (bool)$primary;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isPrimary() {
		return (bool)$this->primary;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isEmpty() {
		return parent::isEmpty()
		&& !$this->primary;
	}

	/**
	 * Get address created date/time
	 *
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Get address last update date/time
	 *
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/*---FAKE TYPE (todo: get rid of this)*/
	/**
	 * @return Collection
	 */
	public function getTypes() {
		return new ArrayCollection();
	}

	/**
	 * @param Collection $types
	 * @return $this
	 */
	public function setTypes(Collection $types) {
		return $this;
	}


}