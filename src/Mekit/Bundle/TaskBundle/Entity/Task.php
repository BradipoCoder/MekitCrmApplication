<?php
namespace Mekit\Bundle\TaskBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Model\ExtendAccount;
use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\EventBundle\Entity\EventInterface;
use Mekit\Bundle\EventBundle\Model\ExtendEvent;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\TaskBundle\Model\ExtendTask;
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
 * @ORM\Entity(repositoryClass="Mekit\Bundle\TaskBundle\Entity\Repository\TaskRepository")
 * @ORM\Table(name="mekit_task", indexes={
 *      @ORM\Index(name="idx_task_name", columns={"name"})
 * })
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_task_index",
 *      routeView="mekit_task_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-flag"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "grouping"={
 *              "groups"={"activity"}
 *          },
 *          "activity"={
 *              "route"="mekit_task_activity_widget",
 *              "acl"="mekit_event_view",
 *              "action_button_widget"="mekit_create_task_button",
 *              "action_link_widget"="mekit_create_task_link"
 *          },
 *          "mekitevent"={
 *              "eventable"=true,
 *              "label"="mekit.task.entity_label",
 *              "icon"="icon-flag",
 *              "view_route_name"="mekit_task_view",
 *              "edit_route_name"="mekit_task_edit"
 *          }
 *      }
 * )
 */
class Task extends ExtendTask implements EventInterface {
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
	 * @ORM\Column(name="name", type="string", length=255, nullable=false)
	 * @Oro\Versioned
	 */
	protected $name;

	/**
	 * @var Event
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\EventBundle\Entity\Event", mappedBy="task", cascade={"all"})
	 * @Soap\ComplexType("Mekit\Bundle\EventBundle\Entity\Event", nillable=false)
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $event;

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
	 * @return Event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @param Event $event
	 * @return $this
	 */
	public function setEvent(Event $event) {
		$this->event = $event;
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getName();
	}
}