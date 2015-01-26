<?php
namespace Mekit\Bundle\CallBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Model\ExtendAccount;
use Mekit\Bundle\CallBundle\Model\ExtendCall;
use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\EventBundle\Model\ExtendEvent;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Mekit\Bundle\RelationshipBundle\Entity\Referenceable;
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
 * @ORM\Entity(repositoryClass="Mekit\Bundle\CallBundle\Entity\Repository\CallRepository")
 * @ORM\Table(name="mekit_call", indexes={
 *      @ORM\Index(name="idx_call_direction", columns={"direction"}),
 * })
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_call_index",
 *      routeView="mekit_call_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-phone"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "mekitevent"={
 *              "eventable"=true,
 *              "label"="mekit.call.entity_label",
 *              "icon"="icon-phone",
 *              "view_route_name"="mekit_call_view",
 *              "edit_route_name"="mekit_call_edit"
 *          },
 *          "relationship"={
 *              "referenceable"=true,
 *              "label"="mekit.call.entity_plural_label",
 *              "can_reference_itself"=false,
 *              "datagrid_name_list"="calls-related-relationship",
 *              "datagrid_name_select"="calls-related-select",
 *              "autocomplete_search_columns"={"i2s"}
 *          }
 *      }
 * )
 */
class Call extends ExtendCall implements Referenceable {
	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Soap\ComplexType("int", nillable=true)
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", length=65535, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=250
	 *          },
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *      }
	 * )
	 */
	protected $description;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=4, nullable=false)
	 * @Soap\ComplexType("string", nillable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "importexport"={
	 *              "order"=250
	 *          },
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *      }
	 * )
	 */
	protected $direction;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="outcome", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=90,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $outcome;

	/**
	 * @var Event
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\EventBundle\Entity\Event", mappedBy="call", cascade={"all"})
	 * @Soap\ComplexType("Mekit\Bundle\EventBundle\Entity\Event", nillable=false)
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $event;

	/**
	 * @var ReferenceableElement
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement", cascade={"persist"}, orphanRemoval=true, mappedBy="call")
	 */
	protected $referenceableElement;

	/**
	 * @return ReferenceableElement
	 */
	public function getReferenceableElement() {
		return $this->referenceableElement;
	}

	/**
	 * @param ReferenceableElement $referenceableElement
	 */
	public function setReferenceableElement(ReferenceableElement $referenceableElement) {
		$this->referenceableElement = $referenceableElement;
		$referenceableElement->setCall($this);
	}


	/**
	 * Constructor
	 */
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
	 * @param int $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
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
	 * @return string
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * @param string $direction
	 * @return $this
	 */
	public function setDirection($direction) {
		$this->direction = $direction;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getOutcome() {
		return $this->outcome;
	}

	/**
	 * @param ListItem $outcome
	 * @return $this
	 */
	public function setOutcome($outcome) {
		$this->outcome = $outcome;
		return $this;
	}

	/**
	 * @return Event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @param Event $event
	 * @return $this
	 */
	public function setEvent($event) {
		$this->event = $event;
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getEvent()->getName();
	}
}