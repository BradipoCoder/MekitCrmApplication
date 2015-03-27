<?php
namespace Mekit\Bundle\ContactBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\MappedSuperclass
 */
class Addresses extends ListItems
{
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
	 * Remove address
	 *
	 * @param Address $address
	 * @return $this
	 */
	public function removeAddress(Address $address) {
		if ($this->addresses->contains($address)) {
			$this->addresses->removeElement($address);
		}

		return $this;
	}

	/**
	 * @param Collection|\Oro\Bundle\AddressBundle\Entity\AbstractAddress[] $addresses
	 * @return $this
	 */
	public function resetAddresses($addresses) {
		return $this->setAddresses($addresses);
	}

	/**
	 * Add address
	 *
	 * @param Address $address
	 *
	 * @return $this
	 */
	public function addAddress(Address $address) {
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
	 * Get addresses
	 *
	 * @return Collection|Address[]
	 */
	public function getAddresses() {
		return $this->addresses;
	}

	/**
	 * Set addresses.
	 *
	 * @param Collection|Address[] $addresses
	 * @return $this
	 */
	public function setAddresses($addresses) {
		if ($this->addresses) {
			$this->addresses->clear();
		}
		foreach ($addresses as $address) {
			$this->addAddress($address);
		}

		return $this;
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
	 * @param Address $address
	 * @return bool
	 */
	public function hasAddress(Address $address) {
		return $this->getAddresses()->contains($address);
	}
}