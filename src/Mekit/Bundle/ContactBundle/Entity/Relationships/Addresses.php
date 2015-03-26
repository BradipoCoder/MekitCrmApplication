<?php
namespace Mekit\Bundle\ContactBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Mekit\Bundle\ContactInfoBundle\Entity\Address;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\AddressBundle\Entity\AddressType;

/**
 * @ORM\MappedSuperclass
 */
class Addresses extends ListItems {
	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactInfoBundle\Entity\Address", mappedBy="owner_contact", cascade={"all"}, orphanRemoval=true)
	 * @ORM\OrderBy({"primary" = "DESC"})
	 * @Soap\ComplexType("Mekit\Bundle\ContactInfoBundle\Entity\Address[]", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "full"=true,
	 *              "order"=250
	 *          }
	 *      }
	 * )
	 */
	protected $addresses;

	public function __construct() {
		parent::__construct();
		$this->addresses = new ArrayCollection();
	}

	/**
	 * Set addresses.
	 *
	 * @param Collection|AbstractAddress[] $addresses
	 * @return $this
	 */
	public function resetAddresses($addresses) {
		if ($this->addresses) {
			$this->addresses->clear();
		}
		foreach ($addresses as $address) {
			$this->addAddress($address);
		}
		return $this;
	}

	/**
	 * Remove address
	 *
	 * @param AbstractAddress $address
	 * @return $this
	 */
	public function removeAddress(AbstractAddress $address) {
		if ($this->addresses->contains($address)) {
			$this->addresses->removeElement($address);
		}
		return $this;
	}

	/**
	 * Get addresses
	 *
	 * @return Collection|AbstractAddress[]
	 */
	public function getAddresses() {
		return $this->addresses;
	}

	/**
	 * @param AbstractAddress $address
	 * @return bool
	 */
	public function hasAddress(AbstractAddress $address) {
		return $this->getAddresses()->contains($address);
	}

	/**
	 * Add address
	 *
	 * @param AbstractAddress $address
	 *
	 * @return $this
	 */
	public function addAddress(AbstractAddress $address) {
		/** @var Address $address */
		if (!$this->addresses->contains($address)) {
			$this->addresses->add($address);
			$address->setOwnerContact($this);
		}
		return $this;
	}

	/**
	 * Gets primary address if it's available.
	 *
	 * @return Address|null
	 */
	public function getPrimaryAddress() {
		$result = null;
		/** @var Address $address */
		foreach ($this->getAddresses() as $address) {
			if ($address->isPrimary()) {
				$result = $address;
				break;
			}
		}
		return $result;
	}

	/**
	 * @param Address $address
	 * @return $this
	 */
	public function setPrimaryAddress(Address $address) {
		if ($this->hasAddress($address)) {
			$address->setPrimary(true);
			/** @var Address $otherAddress */
			foreach ($this->getAddresses() as $otherAddress) {
				if (!$address->isEqual($otherAddress)) {
					$otherAddress->setPrimary(false);
				}
			}
		}
		return $this;
	}

	/**
	 * Gets address type if it's available.
	 *
	 * @param Address     $address
	 * @param AddressType $addressType
	 * @return $this
	 */
	public function setAddressType(Address $address, AddressType $addressType) {
		if ($this->hasAddress($address)) {
			$address->addType($addressType);
			/** @var Address $otherAddress */
			foreach ($this->getAddresses() as $otherAddress) {
				if (!$address->isEqual($otherAddress)) {
					$otherAddress->removeType($addressType);
				}
			}
		}

		return $this;
	}

	/**
	 * Gets one address that has specified type.
	 *
	 * @param AddressType $type
	 * @return Address|null
	 */
	public function getAddressByType(AddressType $type) {
		return $this->getAddressByTypeName($type->getName());
	}

	/**
	 * Gets one address that has specified type name.
	 *
	 * @param string $typeName
	 * @return Address|null
	 */
	public function getAddressByTypeName($typeName) {
		$result = null;
		/** @var Address $address */
		foreach ($this->getAddresses() as $address) {
			if ($address->hasTypeWithName($typeName)) {
				$result = $address;
				break;
			}
		}
		return $result;
	}

}