<?php
namespace Mekit\Bundle\AccountBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
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
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactInfoBundle\Entity\Address", mappedBy="owner_account", cascade={"all"}, orphanRemoval=true)
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
	 * @param AbstractAddress $address
	 * @return bool
	 */
	public function hasAddress(AbstractAddress $address) {
		return $this->getAddresses()->contains($address);
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
	 * Set addresses.
	 *
	 * @param Collection|AbstractAddress[] $addresses
	 * @return $this
	 */
	public function setAddresses($addresses) {
		$this->addresses->clear();

		foreach ($addresses as $address) {
			$this->addAddress($address);
		}

		return $this;
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
			$address->setOwnerAccount($this);
		}

		return $this;
	}
}