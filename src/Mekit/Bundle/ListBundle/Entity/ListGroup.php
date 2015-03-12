<?php
namespace Mekit\Bundle\ListBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\ListBundle\Entity\Repository\ListGroupRepository")
 * @ORM\Table(name="mekit_list_group",
 *      indexes={
 *          @ORM\Index(name="idx_lg_system", columns={"system"}),
 *          @ORM\Index(name="idx_lg_owner", columns={"business_unit_id"}),
 *          @ORM\Index(name="idx_lg_organization", columns={"organization_id"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unq_lg_name", columns={"name"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_list_index",
 *      routeView="mekit_list_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-reorder"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "ownership"={
 *              "owner_type"="BUSINESS_UNIT",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="business_unit_id",
 *               "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          }
 *      }
 * )
 */
class ListGroup
{
	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=32, nullable=false)
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	protected $label;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	protected $emptyValue;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 */
	protected $required;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 */
	protected $system;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", length=65535, nullable=true)
	 */
	protected $description;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem", mappedBy="listGroup", fetch="EAGER")
	 * @ORM\OrderBy({"id" = "ASC"})
	 */
	protected $items;

	/**
	 * @var BusinessUnit
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\BusinessUnit")
	 * @ORM\JoinColumn(name="business_unit_id", referencedColumnName="id", onDelete="SET NULL")
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
	 * Constructor
	 */
	public function __construct() {
		$this->system = false;
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
	public function getEmptyValue() {
		return $this->emptyValue;
	}

	/**
	 * @param string $emptyValue
	 * @return $this
	 */
	public function setEmptyValue($emptyValue) {
		$this->emptyValue = $emptyValue;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isRequired() {
		return $this->required;
	}

	/**
	 * @param boolean $required
	 * @return $this
	 */
	public function setRequired($required) {
		$this->required = $required;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isSystem() {
		return $this->system;
	}

	/**
	 * @param boolean $system
	 * @return $this
	 */
	public function setSystem($system) {
		$this->system = $system;

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
	 * @return Collection
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @return bool
	 */
	public function hasItems() {
		return (!$this->items->isEmpty());
	}

	/**
	 * @param Collection $items
	 * @return $this
	 */
	public function setItems($items) {
		$this->items->clear();
		foreach ($items as $item) {
			$this->addItem($item);
		}

		return $this;
	}

	/**
	 * Add ListItem
	 *
	 * @param ListItem $item
	 * @return $this
	 */
	public function addItem(ListItem $item) {
		if (!$this->items->contains($item)) {
			$this->items->add($item);
			$item->setListGroup($this);
		}

		return $this;
	}

	/**
	 * @return BusinessUnit
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param BusinessUnit $owningBusinessUnit
	 * @return $this
	 */
	public function setOwner($owningBusinessUnit) {
		$this->owner = $owningBusinessUnit;

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
	 * @param ExecutionContext $context
	 */
	public function validate(ExecutionContext $context) {
		if ($this->isSystem() !== true) {
			$this->setSystem(false);
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getName();
	}

}