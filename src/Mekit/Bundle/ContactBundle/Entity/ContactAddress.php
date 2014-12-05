<?php
namespace Mekit\Bundle\ContactBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\AddressBundle\Entity\AbstractTypedAddress;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Table("mekit_contact_address")
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
 * @ORM\Entity
 */
class ContactAddress extends AbstractTypedAddress {
	/**
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", inversedBy="addresses", cascade={"persist"})
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $owner;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Oro\Bundle\AddressBundle\Entity\AddressType", cascade={"persist"})
	 * @ORM\JoinTable(
	 *     name="mekit_contact_adr_to_adr_type",
	 *     joinColumns={@ORM\JoinColumn(name="contact_address_id", referencedColumnName="id", onDelete="CASCADE")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="type_name", referencedColumnName="name")}
	 * )
	 * @Soap\ComplexType("string[]", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=200,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 **/
	protected $types;

	/**
	 * Set Contact as owner.
	 * @param Contact $owner
	 */
	public function setOwner(Contact $owner = null) {
		$this->owner = $owner;
	}

	/**
	 * Get owner contact.
	 *
	 * @return Contact
	 */
	public function getOwner() {
		return $this->owner;
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

}