<?php
namespace Mekit\Bundle\ContactInfoBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\AddressBundle\Entity\Country;
use Oro\Bundle\AddressBundle\Entity\Region;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\FormBundle\Entity\EmptyItem;
use Oro\Bundle\FormBundle\Entity\PrimaryItem;
use Oro\Bundle\LocaleBundle\Model\AddressInterface;

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
class Address implements PrimaryItem, EmptyItem, AddressInterface
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Soap\ComplexType("int", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $id;

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

	/**
	 * @var string
	 *
	 * @ORM\Column(name="label", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=10
	 *          }
	 *      }
	 * )
	 */
	protected $label;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="street", type="string", length=500, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=80,
	 *              "identity"=true
	 *          }
	 *      }
	 * )
	 */
	protected $street;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="street2", type="string", length=500, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=90
	 *          }
	 *      }
	 * )
	 */
	protected $street2;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="city", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=110,
	 *              "identity"=true
	 *          }
	 *      }
	 * )
	 */
	protected $city;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=100,
	 *              "identity"=true
	 *          }
	 *      }
	 * )
	 */
	protected $postalCode;

	/**
	 * @var Country
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\AddressBundle\Entity\Country")
	 * @ORM\JoinColumn(name="country_code", referencedColumnName="iso2_code")
	 * @Soap\ComplexType("string", nillable=false)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=140,
	 *              "short"=true,
	 *              "identity"=true
	 *          }
	 *      }
	 * )
	 */
	protected $country;

	/**
	 * @var Region
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\AddressBundle\Entity\Region")
	 * @ORM\JoinColumn(name="region_code", referencedColumnName="combined_code")
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=130,
	 *              "short"=true,
	 *              "identity"=true
	 *          }
	 *      }
	 * )
	 */
	protected $region;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="region_text", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=120
	 *          }
	 *      }
	 * )
	 */
	protected $regionText;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="organization", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=20
	 *          }
	 *      }
	 * )
	 */
	protected $organization;

	/**
	 * @var \DateTime $created
	 *
	 * @ORM\Column(type="datetime")
	 * @ConfigField(
	 *      defaultValues={
	 *          "entity"={
	 *              "label"="oro.ui.created_at"
	 *          },
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $created;

	/**
	 * @var \DateTime $updated
	 *
	 * @ORM\Column(type="datetime")
	 * @ConfigField(
	 *      defaultValues={
	 *          "entity"={
	 *              "label"="oro.ui.updated_at"
	 *          },
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $updated;


	public function __construct() {
		$this->primary = false;
	}

	/**
	 * @return bool
	 */
	public function isPrimary() {
		return (bool)$this->primary;
	}

	/**
	 * @param bool $primary
	 * @return $this
	 */
	public function setPrimary($primary) {
		if ($primary) {
			$owner = $this->getAddressOwner();
			if ($owner) {
				$addresses = $owner->getAddresses();
				/** @var Address $address */
				foreach ($addresses as $address) {
					$address->setPrimary(false);
				}
			}
		}
		$this->primary = (bool)$primary;

		return $this;
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
	 * @return bool
	 */
	public function isAccountAddress() {
		return (!is_null($this->owner_account));
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
	 * @return bool
	 */
	public function isContactAddress() {
		return (!is_null($this->owner_contact));
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
	 * Get name of region
	 *
	 * @return string
	 */
	public function getRegionName() {
		return $this->getRegion() ? $this->getRegion()->getName() : $this->getRegionText();
	}

	/**
	 * Get region
	 *
	 * @return Region
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * Set region
	 *
	 * @param Region $region
	 * @return $this
	 */
	public function setRegion($region) {
		$this->region = $region;

		return $this;
	}

	/**
	 * Get region test
	 *
	 * @return string
	 */
	public function getRegionText() {
		return $this->regionText;
	}

	/**
	 * Set region text
	 *
	 * @param string $regionText
	 * @return $this
	 */
	public function setRegionText($regionText) {
		$this->regionText = $regionText;

		return $this;
	}

	/**
	 * Get code of region
	 *
	 * @return string
	 */
	public function getRegionCode() {
		return $this->getRegion() ? $this->getRegion()->getCode() : '';
	}

	/**
	 * Get name of country
	 *
	 * @return string
	 */
	public function getCountryName() {
		return $this->getCountry() ? $this->getCountry()->getName() : '';
	}

	/**
	 * Get country
	 *
	 * @return Country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Set country
	 *
	 * @param Country $country
	 * @return $this
	 */
	public function setCountry($country) {
		$this->country = $country;

		return $this;
	}

	/**
	 * Get country ISO3 code
	 *
	 * @return string
	 */
	public function getCountryIso3() {
		return $this->getCountry() ? $this->getCountry()->getIso3Code() : '';
	}

	/**
	 * Get country ISO2 code
	 *
	 * @return string
	 */
	public function getCountryIso2() {
		return $this->getCountry() ? $this->getCountry()->getIso2Code() : '';
	}

	/**
	 * Get organization
	 *
	 * @return string
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * Sets organization
	 *
	 * @param string $organization
	 * @return $this
	 */
	public function setOrganization($organization) {
		$this->organization = $organization;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param \DateTime $created
	 * @return $this
	 */
	public function setCreated($created) {
		$this->created = $created;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * @param \DateTime $updated
	 * @return $this
	 */
	public function setUpdated($updated) {
		$this->updated = $updated;

		return $this;
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
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Set label
	 *
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label) {
		$this->label = $label;

		return $this;
	}

	/**
	 * Get street
	 *
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * Set street
	 *
	 * @param string $street
	 * @return $this
	 */
	public function setStreet($street) {
		$this->street = $street;

		return $this;
	}

	/**
	 * Get street2
	 *
	 * @return string
	 */
	public function getStreet2() {
		return $this->street2;
	}

	/**
	 * Set street2
	 *
	 * @param string $street2
	 * @return $this
	 */
	public function setStreet2($street2) {
		$this->street2 = $street2;

		return $this;
	}

	/**
	 * Get city
	 *
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * Set city
	 *
	 * @param string $city
	 * @return $this
	 */
	public function setCity($city) {
		$this->city = $city;

		return $this;
	}

	/**
	 * Get region or region string
	 *
	 * @return Region|string
	 */
	public function getUniversalRegion() {
		if (!empty($this->regionText)) {
			return $this->regionText;
		} else {
			return $this->region;
		}
	}

	/**
	 * Get postal_code
	 *
	 * @return string
	 */
	public function getPostalCode() {
		return $this->postalCode;
	}

	/**
	 * Set postal_code
	 *
	 * @param string $postalCode
	 * @return $this
	 */
	public function setPostalCode($postalCode) {
		$this->postalCode = $postalCode;

		return $this;
	}

	/**
	 * Check if entity is empty.
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return empty($this->label)
		       && empty($this->street)
		       && empty($this->street2)
		       && empty($this->city)
		       && empty($this->region)
		       && empty($this->regionText)
		       && empty($this->country)
		       && empty($this->postalCode);
	}

	/**
	 * @param mixed $other
	 * @return bool
	 */
	public function isEqual($other) {
		$class = ClassUtils::getClass($this);

		if (!$other instanceof $class) {
			return false;
		}

		/** @var AbstractAddress $other */
		if ($this->getId() && $other->getId()) {
			return $this->getId() == $other->getId();
		}

		if ($this->getId() || $other->getId()) {
			return false;
		}

		return $this === $other;
	}

	/**
	 * Convert address to string
	 *
	 * @return string
	 */
	public function __toString() {
		$data = array(
			$this->getLabel(),
			',',
			$this->getStreet(),
			$this->getStreet2(),
			$this->getCity(),
			$this->getUniversalRegion(),
			',',
			$this->getCountry(),
			$this->getPostalCode(),
		);

		$str = implode(' ', $data);
		$check = trim(str_replace(',', '', $str));

		return empty($check) ? '' : $str;
	}

	/**
	 * Pre persist event listener
	 *
	 * @ORM\PrePersist
	 */
	public function doPrePersist() {
		$this->created = new \DateTime('now', new \DateTimeZone('UTC'));
		$this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
	}

	/**
	 * Pre update event handler
	 *
	 * @ORM\PreUpdate
	 */
	public function doPreUpdate() {
		$this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
	}
}