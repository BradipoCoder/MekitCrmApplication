<?php
namespace Mekit\Bundle\ContactBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\AccountBundle\Entity\AccountEmail;
use Mekit\Bundle\AccountBundle\Entity\AccountPhone;
use Mekit\Bundle\ContactBundle\Model\ExtendContact;
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
 * @ORM\Entity
 * @ORM\Table(
 *      name="mekit_contact",
 *      indexes={@ORM\Index(name="contact_name_idx",columns={"last_name", "first_name"})}
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_contact_index",
 *      routeView="mekit_contact_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-group",
 *              "contact_information"={
 *                  "email"={
 *                      {"fieldName"="primaryEmail"}
 *                  },
 *                  "phone"={
 *                      {"fieldName"="primaryPhone"}
 *                  }
 *              }
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
 *          "form"={
 *              "form_type"="orocrm_contact_select",
 *              "grid_name"="contacts-select-grid",
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          }
 *      }
 * )
 */
class Contact extends ExtendContact implements Taggable, EmailOwnerInterface {
	/*
	 * Fields have to be duplicated here to enable dataaudit and soap transformation only for contact
	 */
	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
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
	 * @ORM\Column(name="name_prefix", type="string", length=16, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=20
	 *          }
	 *      }
	 * )
	 */
	protected $namePrefix;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="first_name", type="string", length=128)
	 * @Soap\ComplexType("string")
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
	protected $firstName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="middle_name", type="string", length=64, nullable=true)
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
	protected $middleName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="last_name", type="string", length=128)
	 * @Soap\ComplexType("string")
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "identity"=true,
	 *              "order"=50
	 *          }
	 *      }
	 * )
	 */
	protected $lastName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name_suffix", type="string", length=64, nullable=true)
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
	protected $nameSuffix;

	/**
	 * @var Account
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", inversedBy="contacts")
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Soap\ComplexType("integer", nillable=true)
	 */
	protected $account;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="gender", type="string", length=8, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=70
	 *          }
	 *      }
	 * )
	 */
	protected $gender;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="birthday", type="date", nullable=true)
	 * @Soap\ComplexType("date", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=160
	 *          }
	 *      }
	 * )
	 */
	protected $birthday;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text", nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=80
	 *          }
	 *      }
	 * )
	 */
	protected $description;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="skype", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=110
	 *          }
	 *      }
	 * )
	 */
	protected $skype;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=120
	 *          }
	 *      }
	 * )
	 */
	protected $twitter;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=130
	 *          }
	 *      }
	 * )
	 */
	protected $facebook;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="google_plus", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=140
	 *          }
	 *      }
	 * )
	 */
	protected $googlePlus;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="linkedin", type="string", length=255, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=150
	 *          }
	 *      }
	 * )
	 */
	protected $linkedIn;

	//$reportsTo

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="job_title", referencedColumnName="id", nullable=true)
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
	protected $jobTitle;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\AccountBundle\Entity\AccountPhone", mappedBy="ownerContact",
	 *    cascade={"all"}, orphanRemoval=true
	 * ))
	 * @ORM\OrderBy({"primary" = "DESC"})
	 * @Soap\ComplexType("Mekit\Bundle\AccountBundle\Entity\AccountPhone[]", nillable=true)
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
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\AccountBundle\Entity\AccountEmail", mappedBy="ownerContact",
	 *    cascade={"all"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"primary" = "DESC"})
	 * @Soap\ComplexType("Mekit\Bundle\AccountBundle\Entity\AccountEmail[]", nillable=true)
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
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ContactBundle\Entity\ContactAddress",
	 *    mappedBy="owner", cascade={"all"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"primary" = "DESC"})
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
	 * @return Account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param Account $account
	 * @return $this
	 */
	public function setAccount($account) {
		$this->account = $account;
		return $this;
	}

	/**
	 * @param User $assignedTo
	 * @return $this
	 */
	public function setAssignedTo($assignedTo) {
		$this->assignedTo = $assignedTo;
		return $this;
	}

	/**
	 * @return User
	 */
	public function getAssignedTo() {
		return $this->assignedTo;
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
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $skype
	 * @return $this
	 */
	public function setSkype($skype) {
		$this->skype = $skype;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSkype() {
		return $this->skype;
	}

	/**
	 * @param string $facebookUrl
	 * @return $this
	 */
	public function setFacebook($facebookUrl) {
		$this->facebook = $facebookUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFacebook() {
		return $this->facebook;
	}

	/**
	 * @param string $googlePlusUrl
	 * @return $this
	 */
	public function setGooglePlus($googlePlusUrl) {
		$this->googlePlus = $googlePlusUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getGooglePlus() {
		return $this->googlePlus;
	}

	/**
	 * @param string $linkedInUrl
	 * @return $this
	 */
	public function setLinkedIn($linkedInUrl) {
		$this->linkedIn = $linkedInUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLinkedIn() {
		return $this->linkedIn;
	}

	/**
	 * @param string $twitterUrl
	 * @return $this
	 */
	public function setTwitter($twitterUrl) {
		$this->twitter = $twitterUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTwitter() {
		return $this->twitter;
	}

	/**
	 * @return ListItem
	 */
	public function getJobTitle() {
		return $this->jobTitle;
	}

	/**
	 * @param ListItem $jobTitle
	 * @return $this
	 */
	public function setJobTitle($jobTitle) {
		$this->jobTitle = $jobTitle;
		return $this;
	}

	/**
	 * Set phones.
	 * This method could not be named setPhones because of bug CRM-253.
	 *
	 * @param Collection|AccountPhone[] $phones
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
	 * @param AccountPhone $phone
	 * @return $this
	 */
	public function addPhone(AccountPhone $phone) {
		if (!$this->phones->contains($phone)) {
			$this->phones->add($phone);
			$phone->setOwnerContact($this);
		}
		return $this;
	}

	/**
	 * Remove phone
	 *
	 * @param AccountPhone $phone
	 * @return $this
	 */
	public function removePhone(AccountPhone $phone) {
		if ($this->phones->contains($phone)) {
			$this->phones->removeElement($phone);
		}
		return $this;
	}

	/**
	 * Get phones
	 *
	 * @return Collection|AccountPhone[]
	 */
	public function getPhones() {
		return $this->phones;
	}

	/**
	 * @param AccountPhone $phone
	 * @return bool
	 */
	public function hasPhone(AccountPhone $phone) {
		return $this->getPhones()->contains($phone);
	}

	/**
	 * Gets primary phone if it's available.
	 *
	 * @return AccountPhone|null
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
	 * @param AccountPhone $phone
	 * @return $this
	 */
	public function setPrimaryPhone(AccountPhone $phone) {
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
	 * Set emails
	 *
	 * @param Collection|AccountEmail[] $emails
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
	 * @param AccountEmail $email
	 * @return $this
	 */
	public function addEmail(AccountEmail $email) {
		if (!$this->emails->contains($email)) {
			$this->emails->add($email);
			$email->setOwnerContact($this);
		}
		return $this;
	}

	/**
	 * Remove email
	 *
	 * @param AccountEmail $email
	 * @return $this
	 */
	public function removeEmail(AccountEmail $email) {
		if ($this->emails->contains($email)) {
			$this->emails->removeElement($email);
		}
		return $this;
	}

	/**
	 * Get emails
	 *
	 * @return Collection|AccountEmail[]
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
	 * @param AccountEmail $email
	 * @return bool
	 */
	public function hasEmail(AccountEmail $email) {
		return $this->getEmails()->contains($email);
	}

	/**
	 * Gets primary email if it's available.
	 *
	 * @return AccountEmail|null
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
	 * @param AccountEmail $email
	 * @return $this
	 */
	public function setPrimaryEmail(AccountEmail $email) {
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
		/** @var ContactAddress $address */
		if (!$this->addresses->contains($address)) {
			$this->addresses->add($address);
			$address->setOwner($this);
		}
		return $this;
	}

	/**
	 * Gets primary address if it's available.
	 *
	 * @return ContactAddress|null
	 */
	public function getPrimaryAddress() {
		$result = null;
		/** @var ContactAddress $address */
		foreach ($this->getAddresses() as $address) {
			if ($address->isPrimary()) {
				$result = $address;
				break;
			}
		}
		return $result;
	}

	/**
	 * @param ContactAddress $address
	 * @return $this
	 */
	public function setPrimaryAddress(ContactAddress $address) {
		if ($this->hasAddress($address)) {
			$address->setPrimary(true);
			/** @var ContactAddress $otherAddress */
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
	 * @param ContactAddress $address
	 * @param AddressType    $addressType
	 * @return $this
	 */
	public function setAddressType(ContactAddress $address, AddressType $addressType) {
		if ($this->hasAddress($address)) {
			$address->addType($addressType);
			/** @var ContactAddress $otherAddress */
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
	 * @return ContactAddress|null
	 */
	public function getAddressByType(AddressType $type) {
		return $this->getAddressByTypeName($type->getName());
	}

	/**
	 * Gets one address that has specified type name.
	 *
	 * @param string $typeName
	 * @return ContactAddress|null
	 */
	public function getAddressByTypeName($typeName) {
		$result = null;
		/** @var ContactAddress $address */
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
		$name = $this->getNamePrefix() . ' '
			. $this->getFirstName() . ' '
			. $this->getMiddleName() . ' '
			. $this->getLastName() . ' '
			. $this->getNameSuffix();
		$name = preg_replace('/ +/', ' ', $name);
		return (string)trim($name);
	}

	/**
	 * Get entity class name.
	 * TODO: Remove this temporary solution for get 'view' route in twig after EntityConfigBundle is finished
	 * @return string
	 */
	public function getClass() {
		return 'OroCRM\Bundle\ContactBundle\Entity\Contact';
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