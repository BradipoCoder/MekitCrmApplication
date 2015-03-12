<?php

namespace Mekit\Bundle\ListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository")
 * @ORM\Table(name="mekit_list_item",
 *      indexes={
 *          @ORM\Index(name="idx_li_system", columns={"system"}),
 *          @ORM\Index(name="idx_li_owner", columns={"business_unit_id"}),
 *          @ORM\Index(name="idx_li_organization", columns={"organization_id"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_list_index",
 *      routeView="",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-bullseye"
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
class ListItem {
	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	protected $label;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 */
	protected $default_item;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 */
	protected $system;

	/**
	 * @var ListGroup
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListGroup", inversedBy="items")
	 * @ORM\JoinColumn(name="listgroup_id", referencedColumnName="id", onDelete="CASCADE")
	 * @Soap\ComplexType("integer", nillable=true)
	 */
	protected $listGroup;

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
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
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
	 * @return ListGroup
	 */
	public function getListGroup() {
		return $this->listGroup;
	}

	/**
	 * @param ListGroup $listGroup
	 * @return $this
	 */
	public function setListGroup($listGroup) {
		$this->listGroup = $listGroup;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isDefaultItem() {
		return $this->default_item;
	}

	/**
	 * @param boolean $default_item
	 * @return $this
	 */
	public function setDefaultItem($default_item) {
		if ($default_item) {
			$listGroup = $this->getListGroup();
			/** @var ListItem $item */
			foreach ($listGroup->getItems() as $item) {
				$item->setDefaultItem(false);
			}
		}
		$this->default_item = $default_item;
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
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getLabel();
	}
}