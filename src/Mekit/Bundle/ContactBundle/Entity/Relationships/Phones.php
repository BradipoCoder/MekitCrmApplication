<?php
namespace Mekit\Bundle\ContactBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Mekit\Bundle\ContactInfoBundle\Entity\Phone;


class Phones extends Addresses {
	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactInfoBundle\Entity\Phone", mappedBy="owner_contact", cascade={"all"})
	 * @ORM\OrderBy({"primary" = "DESC"})
	 * @Soap\ComplexType("Mekit\Bundle\ContactInfoBundle\Entity\Phone[]", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=220
	 *          }
	 *      }
	 * )
	 */
	protected $phones;

	public function __construct() {
		parent::__construct();
		$this->phones = new ArrayCollection();
	}

	/**
	 * Set phones.
	 * This method could not be named setPhones because of bug CRM-253.
	 *
	 * @param Collection|Phone[] $phones
	 * @return $this
	 */
	public function resetPhones($phones) {
		$this->phones->clear();
		foreach ($phones as $phone) {
			$this->addPhone($phone);
		}
		return $this;
	}

	/**
	 * Add phone
	 *
	 * @param Phone $phone
	 * @return $this
	 */
	public function addPhone(Phone $phone) {
		if (!$this->phones->contains($phone)) {
			$this->phones->add($phone);
			$phone->setOwnerContact($this);
		}
		return $this;
	}

	/**
	 * Remove phone
	 *
	 * @param Phone $phone
	 * @return $this
	 */
	public function removePhone(Phone $phone) {
		if ($this->phones->contains($phone)) {
			$this->phones->removeElement($phone);
		}
		return $this;
	}

	/**
	 * Get phones
	 *
	 * @return Collection|Phone[]
	 */
	public function getPhones() {
		return $this->phones;
	}

	/**
	 * @param Phone $phone
	 * @return bool
	 */
	public function hasPhone(Phone $phone) {
		return $this->getPhones()->contains($phone);
	}

	/**
	 * Gets primary phone if it's available.
	 *
	 * @return Phone|null
	 */
	public function getPrimaryPhone() {
		$result = null;
		foreach ($this->getPhones() as $phone) {
			if ($phone->isPrimary()) {
				$result = $phone;
				break;
			}
		}
		return $result;
	}

	/**
	 * @param Phone $phone
	 * @return $this
	 */
	public function setPrimaryPhone(Phone $phone) {
		if ($this->hasPhone($phone)) {
			$phone->setPrimary(true);
			foreach ($this->getPhones() as $otherPhone) {
				if (!$phone->isEqual($otherPhone)) {
					$otherPhone->setPrimary(false);
				}
			}
		}
		return $this;
	}

}