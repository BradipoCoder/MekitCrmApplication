<?php
namespace Mekit\Bundle\ProjectBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;

use Mekit\Bundle\ProjectBundle\Model\ExtendProject;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *      name="mekit_project",
 *      indexes={
 *          @ORM\Index(name="idx_project_owner", columns={"owner_id"}),
 *          @ORM\Index(name="idx_project_organization", columns={"organization_id"}),
 *          @ORM\Index(name="idx_project_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_project_updated_at", columns={"updatedAt"}),
 *          @ORM\Index(name="idx_project_name", columns={"name"}),
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_project_index",
 *      routeView="mekit_project_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-tasks"
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
 *          "dataaudit"={
 *              "auditable"=true
 *          }
 *      }
 * )
 */
class Project extends ExtendProject implements Taggable {
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
	 * @ORM\Column(name="name", type="string", length=255)
	 * @Soap\ComplexType("string")
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
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
	 * @ORM\Column(name="description", type="text", nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=30
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
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name) {
		$this->name = $name;
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
	 * @return string
	 */
	public function __toString() {
		return $this->getName();
	}

	/**
	 * Pre persist event listener
	 *
	 * @ORM\PrePersist
	 */
	public function doPrePersist() {
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