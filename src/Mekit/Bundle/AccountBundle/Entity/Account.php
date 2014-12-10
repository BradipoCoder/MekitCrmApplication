<?php
namespace Mekit\Bundle\AccountBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Model\ExtendAccount;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;
use Mekit\Bundle\ContactInfoBundle\Entity\Email;
use Mekit\Bundle\ContactInfoBundle\Entity\Phone;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Oro\Bundle\AddressBundle\Entity\AbstractAddress;
use Oro\Bundle\AddressBundle\Entity\AddressType;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EmailBundle\Entity\EmailOwnerInterface;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;


/**
 * @ORM\Entity()
 * @ORM\Table(name="mekit_account",
 *      indexes={
 *          @ORM\Index(name="idx_account_owner", columns={"owner_id"}),
 *          @ORM\Index(name="idx_account_organization", columns={"organization_id"}),
 *          @ORM\Index(name="idx_account_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_account_updated_at", columns={"updatedAt"}),
 *          @ORM\Index(name="idx_account_name", columns={"name"}),
 *          @ORM\Index(name="idx_account_vatid", columns={"vatid"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_account_index",
 *      routeView="mekit_account_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-suitcase"
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "merge"={
 *              "enable"=true
 *          },
 *          "form"={
 *              "form_type"="mekit_account_select",
 *              "grid_name"="accounts-select-grid",
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          }
 *      }
 * )
 */
class Account extends ExtendAccount implements Taggable, EmailOwnerInterface {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Soap\ComplexType("int", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=10
	 *          }
	 *      }
	 * )
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Soap\ComplexType("string")
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "merge"={
	 *              "display"=true
	 *          },
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "identity"=true,
	 *              "order"=20
	 *          }
	 *      }
	 * )
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=16, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "identity"=true,
	 *              "order"=30
	 *          }
	 *      }
	 * )
	 */
	protected $vatid;

	/**
	 * @var string - National Insurance Number (in Italy it can be associated to an Account)
	 *
	 * @ORM\Column(type="string", length=24, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=40
	 *          }
	 *      }
	 * )
	 */
	protected $nin;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=128, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=50
	 *          }
	 *      }
	 * )
	 */
	protected $website;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactInfoBundle\Entity\Phone", mappedBy="owner_account", cascade={"all"})
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

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=16, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=60
	 *          }
	 *      }
	 * )
	 */
	protected $fax;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", length=65535, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=70
	 *          }
	 *      }
	 * )
	 */
	protected $description;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=90,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $type;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="state", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=91,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $state;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="industry", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=92,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $industry;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="source", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=93,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $source;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactBundle\Entity\Contact", mappedBy="account", cascade={"all"}, orphanRemoval=true)
	 * @ORM\OrderBy({"id" = "ASC"})
	 * @Soap\ComplexType("Mekit\Bundle\ContactBundle\Entity\Contact[]", nillable=true)
	 */
	protected $contacts;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, nullable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $email;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactInfoBundle\Entity\Email", mappedBy="owner_account", cascade={"all"})
	 * @ORM\OrderBy({"primary" = "DESC"})
	 * @Soap\ComplexType("Mekit\Bundle\ContactInfoBundle\Entity\Email[]", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=210
	 *          }
	 *      }
	 * )
	 */
	protected $emails;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactInfoBundle\Entity\Address", mappedBy="owner_account", cascade={"all"})
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

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="assigned_to", referencedColumnName="id", onDelete="SET NULL")
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=200,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $assignedTo;


	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=100,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $owner;

	/**
	 * @var Organization
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
	 * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $organization;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(type="datetime")
	 * @Soap\ComplexType("dateTime", nillable=true)
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
	protected $createdAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(type="datetime", nullable=true)
	 * @Soap\ComplexType("dateTime", nillable=true)
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
	protected $updatedAt;

	/**
	 * @var ArrayCollection $tags
	 * @ConfigField(
	 *      defaultValues={
	 *          "merge"={
	 *              "display"=true
	 *          }
	 *      }
	 * )
	 */
	protected $tags;


	public function __construct() {
		parent::__construct();
		$this->phones = new ArrayCollection();
		$this->emails = new ArrayCollection();
		$this->addresses = new ArrayCollection();
		$this->tags = new ArrayCollection();
	}

	/**
	 * Returns the account unique id.
	 *
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param  int $id
	 * @return Account
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set account name
	 *
	 * @param string $name New name
	 * @return $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getVatid() {
		return $this->vatid;
	}

	/**
	 * @param string $vatid
	 * @return $this
	 */
	public function setVatid($vatid) {
		$this->vatid = $vatid;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNin() {
		return $this->nin;
	}

	/**
	 * @param string $nin
	 * @return $this
	 */
	public function setNin($nin) {
		$this->nin = $nin;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getWebsite() {
		return $this->website;
	}

	/**
	 * @param string $website
	 * @return $this
	 */
	public function setWebsite($website) {
		$this->website = $website;
		return $this;
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
			$phone->setOwnerAccount($this);
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

	/**
	 * @return string
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * @param string $fax
	 * @return $this
	 */
	public function setFax($fax) {
		$this->fax = $fax;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param ListItem $type
	 * @return $this
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param ListItem $state
	 * @return $this
	 */
	public function setState($state) {
		$this->state = $state;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getIndustry() {
		return $this->industry;
	}

	/**
	 * @param ListItem $industry
	 * @return $this
	 */
	public function setIndustry($industry) {
		$this->industry = $industry;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param ListItem $source
	 * @return $this
	 */
	public function setSource($source) {
		$this->source = $source;
		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getContacts() {
		return $this->contacts;
	}

	/**
	 * @param Collection $contacts
	 * @return $this
	 */
	public function setContacts($contacts) {
		$this->contacts = $contacts;
		return $this;
	}

	/**
	 * Set emails
	 *
	 * @param Collection|Email[] $emails
	 * @return $this
	 */
	public function resetEmails($emails) {
		$this->emails->clear();
		foreach ($emails as $email) {
			$this->addEmail($email);
		}
		return $this;
	}

	/**
	 * Add email
	 *
	 * @param Email $email
	 * @return $this
	 */
	public function addEmail(Email $email) {
		if (!$this->emails->contains($email)) {
			$this->emails->add($email);
			$email->setOwnerAccount($this);
		}
		return $this;
	}

	/**
	 * Remove email
	 *
	 * @param Email $email
	 * @return $this
	 */
	public function removeEmail(Email $email) {
		if ($this->emails->contains($email)) {
			$this->emails->removeElement($email);
		}
		return $this;
	}

	/**
	 * Get emails
	 *
	 * @return Collection|Email[]
	 */
	public function getEmails() {
		return $this->emails;
	}

	/**
	 * @return null|string
	 */
	public function getEmail() {
		$primaryEmail = $this->getPrimaryEmail();
		if (!$primaryEmail) {
			return null;
		}
		return $primaryEmail->getEmail();
	}

	/**
	 * @param Email $email
	 * @return bool
	 */
	public function hasEmail(Email $email) {
		return $this->getEmails()->contains($email);
	}

	/**
	 * Gets primary email if it's available.
	 *
	 * @return Email|null
	 */
	public function getPrimaryEmail() {
		$result = null;
		foreach ($this->getEmails() as $email) {
			if ($email->isPrimary()) {
				$result = $email;
				break;
			}
		}
		return $result;
	}

	/**
	 * @param Email $email
	 * @return $this
	 */
	public function setPrimaryEmail(Email $email) {
		if ($this->hasEmail($email)) {
			$email->setPrimary(true);
			foreach ($this->getEmails() as $otherEmail) {
				if (!$email->isEqual($otherEmail)) {
					$otherEmail->setPrimary(false);
				}
			}
		}
		return $this;
	}

	/**
	 * Get names of fields contain email addresses
	 *
	 * @return string[]|null
	 */
	public function getEmailFields() {
		return null;
	}

	/**
	 * This is an EmailOwnerInterface requirement
	 *
	 * @return string
	 */
	public function getFirstName() {
		return $this->getName();
	}

	/**
	 * This is an EmailOwnerInterface requirement
	 *
	 * @return string
	 */
	public function getLastName() {
		return $this->getName();
	}

	/**
	 * Set addresses.
	 *
	 * @param Collection|AbstractAddress[] $addresses
	 * @return $this
	 */
	public function resetAddresses($addresses) {
		if($this->addresses) {
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
			$address->setOwnerAccount($this);
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
	 * @param Address $address
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
	 *
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


	/**
	 * @return User
	 */
	public function getAssignedTo() {
		return $this->assignedTo;
	}

	/**
	 * @param User $assignedTo
	 * @return $this
	 */
	public function setAssignedTo(User $assignedTo) {
		$this->assignedTo = $assignedTo;
		return $this;
	}


	/**
	 * Get created date/time
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param \DateTime
	 * @return $this
	 */
	public function setCreatedAt(\DateTime $created) {
		$this->createdAt = $created;
		return $this;
	}

	/**
	 * Get last update date/time
	 *
	 * @return \DateTime
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	/**
	 * @param \DateTime
	 * @return $this
	 */
	public function setUpdatedAt(\DateTime $updated) {
		$this->updatedAt = $updated;
		return $this;
	}

	/**
	 * @return User
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param User $owningUser
	 * @return $this
	 */
	public function setOwner(User $owningUser) {
		$this->owner = $owningUser;
		return $this;
	}

	/**
	 * Set organization
	 *
	 * @param Organization $organization
	 * @return $this
	 */
	public function setOrganization(Organization $organization = null) {
		$this->organization = $organization;
		return $this;
	}

	/**
	 * Get organization
	 *
	 * @return Organization
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTaggableId() {
		return $this->getId();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTags() {
		$this->tags = $this->tags ?: new ArrayCollection();

		return $this->tags;
	}

	/**
	 * @param $tags
	 * @return $this
	 */
	public function setTags($tags) {
		$this->tags = $tags;
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getName();
	}

	/**
	 * Get entity class name. - This is an EmailOwnerInterface requirement
	 * TODO: Remove this temporary solution for get 'view' route in twig after EntityConfigBundle is finished
	 * @return string
	 */
	public function getClass() {
		return 'Mekit\Bundle\AccountBundle\Entity\Account';
	}

	/**
	 * Pre persist event listener
	 *
	 * @ORM\PrePersist
	 */
	public function beforeSave() {
		$this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
		$this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
	}

	/**
	 * Pre update event handler
	 *
	 * @ORM\PreUpdate
	 */
	public function doPreUpdate() {
		$this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
	}
}
