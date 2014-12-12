<?php
namespace Mekit\Bundle\ListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\ListBundle\Entity\Repository\ListGroupRepository")
 * @ORM\Table(name="mekit_list_group",
 *      indexes={
 *          @ORM\Index(name="idx_listgroup_system", columns={"system"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="idx_listgroup_name", columns={"name"}),
 *          @ORM\UniqueConstraint(name="idx_listgroup_item_prefix", columns={"itemPrefix"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks
 * @Oro\Loggable
 */
class ListGroup {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Soap\ComplexType("int", nillable=true)
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=32)
	 * @Soap\ComplexType("string")
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Soap\ComplexType("string")
	 */
	protected $label;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=8)
	 * @Soap\ComplexType("string")
	 */
	protected $itemPrefix;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=64, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 */
	protected $emptyValue;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 * @Soap\ComplexType("boolean")
	 */
	protected $required;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean", options={"default"=0})
	 * @Soap\ComplexType("boolean")
	 */
	protected $system;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", length=65535, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
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
	 */
	protected $items;


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
	public function getItemPrefix() {
		return $this->itemPrefix;
	}

	/**
	 * @param string $itemPrefix
	 * @return $this
	 */
	public function setItemPrefix($itemPrefix) {
		$this->itemPrefix = $itemPrefix;
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
		if (null === $this->items) {
			$this->items = new ArrayCollection();
		}
		return $this->items;
	}

	/**
	 * @return bool
	 */
	public function hasItems() {
		return(!$this->items->isEmpty());
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
	 * @param ExecutionContext $context
	 */
	public function validate(ExecutionContext $context)	{
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