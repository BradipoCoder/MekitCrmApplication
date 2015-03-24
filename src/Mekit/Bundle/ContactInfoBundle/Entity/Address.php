<?php
namespace Mekit\Bundle\ContactInfoBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\AddressBundle\Entity\AddressType;
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
	 * @var Account
	 *
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
	 * @var Contact
	 *
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
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", length=16, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $type;

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

	/* The attributes below are overridden here because we are excluding them from import/export */
	/**
	 * @var string
	 *
	 * @ORM\Column(name="name_prefix", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $namePrefix;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $firstName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $middleName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $lastName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name_suffix", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $nameSuffix;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="organization", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $organization;
	/* End of override */

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


	/**
	 * TODO: ALL THIS(TYPE/TYPES) IS TO BE REMOVED
	 * We will be using 'label' for addresses
	 * We also need to create a custom "AbstractAddress" removing fields:
	 * [$organization, $namePrefix, $firstName, $middleName, $lastName, $nameSuffix]
	 * Also,
	 */

	/**
	 * @return AddressType
	 */
	public function getTypes() {
		$types = new ArrayCollection();
		$types->add(new AddressType($this->type));
		return $types;
	}

	/**
	 * @param Collection $types
	 * @return $this
	 */
	public function setTypes(Collection $types) {
		/** @var AddressType $type */
		$type = $types->first();
		$this->type = $type->getName();
		return $this;
	}

	/**
	 * Get list of address types names
	 *
	 * @return array
	 */
	public function getTypeNames() {
		return [$this->type];
	}

	/**
	 * Gets instance of address type entity by it's name if it exist.
	 *
	 * @param string $typeName
	 * @return AddressType|null
	 */
	public function getTypeByName($typeName) {
		if ($this->type === $typeName) {
			return $this->getTypes();
		}
		return null;
	}

	/**
	 * Checks if address has type with specified name
	 *
	 * @param string $typeName
	 * @return bool
	 */
	public function hasTypeWithName($typeName) {
		return null !== $this->getTypeByName($typeName);
	}

	/**
	 * Get list of address types names(@todo: let's do something about this)
	 *
	 * @return array
	 */
	public function getTypeLabels() {
		return [$this->type];
	}

	/**
	 * @param AddressType $type
	 * @return $this
	 */
	public function addType(AddressType $type) {
		$this->type = $type->getName();
		return $this;
	}

	/**
	 * @param AddressType $type
	 * @return $this
	 */
	public function removeType(AddressType $type) {
		$this->type = null;
		return $this;
	}
}