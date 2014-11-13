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
 * @ORM\Entity(repositoryClass="Mekit\Bundle\ListBundle\Entity\ListItemRepository")
 * @ORM\Table(name="mekit_list_item",
 *      indexes={
 *          @ORM\Index(name="idx_listitem_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_listitem_updated_at", columns={"updatedAt"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="idx_listitem_listgroup_label", columns={"listgroup_id", "label"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_listitem_index",
 *      routeView="mekit_listitem_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-suitcase"
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
class ListItem {
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
	 * @var ListGroup
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListGroup", inversedBy="items")
	 * @ORM\JoinColumn(name="listgroup_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Soap\ComplexType("integer", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $listGroup;

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
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getLabel();
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