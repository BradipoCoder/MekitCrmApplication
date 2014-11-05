<?php

namespace Mekit\Bundle\AccountBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EmailBundle\Model\EmailHolderInterface;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;

use Mekit\Bundle\AccountBundle\Model\ExtendAccount;


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
 *              "owner_column_name"="user_owner_id",
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
class Account extends ExtendAccount implements Taggable, EmailHolderInterface {
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
	 *              "order"=80,
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
	 *
	 * @return Account
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
	 * @return Account
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
	 * @return Account
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
	 * @return Account
	 */
	public function setWebsite($website) {
		$this->website = $website;
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
	 * @return Account
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
	 * @return Account
	 */
	public function setDescription($description) {
		$this->description = $description;
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
	 *
	 * @return Account
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
	 *
	 * @return Account
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
	 *
	 * @return Account
	 */
	public function setOwner(User $owningUser) {
		$this->owner = $owningUser;

		return $this;
	}

	/**
	 * Set organization
	 *
	 * @param Organization $organization
	 * @return Account
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
	 * @param ArrayCollection $tags
	 * @return $this
	 */
	public function setTags($tags) {
		$this->tags = $tags;

		return $this;
	}

	/**
	 * Get the primary email address of the default contact
	 *
	 * @return string
	 */
	public function getEmail() {
		return "Undefined";
	}


	public function __toString() {
		return (string)$this->getName();
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
