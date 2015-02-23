<?php
namespace Mekit\Bundle\ContactBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;

use Mekit\Bundle\ContactBundle\Model\ExtendContact;

use Oro\Bundle\EmailBundle\Entity\EmailOwnerInterface;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;


/**
 * @ORM\Entity()
 * @ORM\Table(
 *      name="mekit_contact",
 *      indexes={
 *          @ORM\Index(name="contact_name_idx", columns={"last_name", "first_name"}),
 *          @ORM\Index(name="idx_contact_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_contact_updated_at", columns={"updatedAt"})
 *      }
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
	 * @ORM\Column(name="last_name", type="string", length=128, nullable=true)
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
	 * Taggable interface requirement
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
	 * Get entity class name.
	 * TODO: Remove this temporary solution for get 'view' route in twig after EntityConfigBundle is finished
	 * @return string
	 */
	public function getClass() {
		return 'OroCRM\Bundle\ContactBundle\Entity\Contact';
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