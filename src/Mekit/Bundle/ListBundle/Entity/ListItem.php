<?php

namespace Mekit\Bundle\ListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository")
 * @ORM\Table(name="mekit_list_item",
 *      indexes={
 *          @ORM\Index(name="idx_listitem_system", columns={"system"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 */
class ListItem {
	/**
	 * @var string
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="string", length=32)
	 * @Soap\ComplexType("string")
	 */
	protected $id;

	/**
	 * @var ListGroup
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListGroup", inversedBy="items", fetch="EAGER")
	 * @ORM\JoinColumn(name="listgroup_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Soap\ComplexType("integer", nillable=true)
	 */
	protected $listGroup;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Soap\ComplexType("string")
	 */
	protected $label;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 * @Soap\ComplexType("boolean")
	 */
	protected $default_item;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 * @Soap\ComplexType("boolean")
	 */
	protected $system;


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
	 * @param ExecutionContext $context
	 */
	public function validate(ExecutionContext $context)	{

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
	public function PrePersist() {
		//forcing id to use prefix set for ListGroup
		$listGroup = $this->getListGroup();
		$prefix = $listGroup->getItemPrefix();
		$this->setId($prefix.$this->getId());
	}

}