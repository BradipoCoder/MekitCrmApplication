<?php

namespace Mekit\Bundle\ListBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="mekit_list_group",
 *      indexes={
 *          @ORM\Index(name="idx_listgroup_owner", columns={"owner_id"}),
 *          @ORM\Index(name="idx_listgroup_organization", columns={"organization_id"}),
 *          @ORM\Index(name="idx_listgroup_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_listgroup_updated_at", columns={"updatedAt"}),
 *          @ORM\Index(name="idx_listgroup_name", columns={"name"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_list_index",
 *      routeView="mekit_list_view",
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
 *          "dataaudit"={
 *              "auditable"=false
 *          }
 *      }
 * )
 */
class ListGroup {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Soap\ComplexType("int", nillable=true)
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=32)
	 * @Soap\ComplexType("string")
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=64)
	 * @Soap\ComplexType("string")
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $label;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", length=65535, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $description;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem",
	 *    mappedBy="listGroup", cascade={"all"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"id" = "ASC"})
	 * @Soap\ComplexType("Mekit\Bundle\ListBundle\Entity\ListItem[]", nillable=true)
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $items;


	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={}
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
	 *          }
	 *      }
	 * )
	 */
	protected $updatedAt;


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->items = new ArrayCollection();
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
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
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label) {
		$this->label = $label;
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
	 * @return $this;
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getItems() {
		if (null === $this->items) {
			$this->items = new ArrayCollection();
		}
		return $this->items;
	}

	/**
	 * @param Collection $items
	 * @return $this
	 */
	public function setItems($items) {
		$this->items = $items;
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
	 *
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
	 *
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
	 * @return string
	 */
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