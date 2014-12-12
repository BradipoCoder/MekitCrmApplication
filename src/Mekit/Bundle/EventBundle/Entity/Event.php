<?php
namespace Mekit\Bundle\EventBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Model\ExtendAccount;
use Mekit\Bundle\CallBundle\Entity\Call;
use Mekit\Bundle\EventBundle\Model\ExtendEvent;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;
use Mekit\Bundle\TaskBundle\Entity\Task;
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
 * @ORM\Entity()
 * @ORM\Table(name="mekit_event",
 *      indexes={
 *          @ORM\Index(name="idx_event_owner", columns={"owner_id"}),
 *          @ORM\Index(name="idx_event_organization", columns={"organization_id"}),
 *          @ORM\Index(name="idx_event_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_event_updated_at", columns={"updatedAt"}),
 *          @ORM\Index(name="idx_event_name", columns={"name"}),
 *          @ORM\Index(name="idx_event_type", columns={"type"}),
 *          @ORM\Index(name="idx_event_start_date", columns={"start_date"}),
 *          @ORM\Index(name="idx_event_end_date", columns={"end_date"}),
 *          @ORM\Index(name="idx_event_state", columns={"state"}),
 *          @ORM\Index(name="idx_event_priority", columns={"priority"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="",
 *      routeView="",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-calendar"
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
class Event extends ExtendEvent{
	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
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
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", length=255, nullable=false)
	 * @Oro\Versioned
	 */
	protected $type;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="start_date", type="datetime", nullable=false)
	 * @Oro\Versioned
	 */
	protected $startDate;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="end_date", type="datetime", nullable=true)
	 * @Oro\Versioned
	 */
	protected $endDate;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="duration", type="integer", nullable=true)
	 * @Oro\Versioned
	 */
	protected $duration;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="state", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 */
	protected $state;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="priority", referencedColumnName="id", nullable=true)
	 * @Oro\Versioned
	 */
	protected $priority;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Oro\Versioned
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
	 *      defaultValues={}
	 * )
	 */
	protected $createdAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(type="datetime", nullable=true)
	 * @Soap\ComplexType("dateTime", nillable=true)
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $updatedAt;

	/**
	 * @var Task
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\TaskBundle\Entity\Task", inversedBy="event", cascade={"all"}, orphanRemoval=true)
	 */
	protected $task;

	/**
	 * @var Meeting
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\MeetingBundle\Entity\Meeting", inversedBy="event", cascade={"all"}, orphanRemoval=true)
	 */
	protected $meeting;

	/**
	 * @var Call
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\CallBundle\Entity\Call", inversedBy="event", cascade={"all"}, orphanRemoval=true)
	 */
	protected $call;


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
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * @param \DateTime $startDate
	 * @return $this
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 * @return $this
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * @param int $duration
	 * @return $this
	 */
	public function setDuration($duration) {
		$this->duration = $duration;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param ListItem $state
	 * @return $this
	 */
	public function setState($state) {
		$this->state = $state;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @param ListItem $priority
	 * @return $this
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
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
	 * @return Task
	 */
	public function getTask() {
		return $this->task;
	}

	/**
	 * @param Task $task
	 * @return $this
	 */
	public function setTask(Task $task) {
		$this->task = $task;
		return $this;
	}

	/**
	 * @return Meeting
	 */
	public function getMeeting() {
		return $this->meeting;
	}

	/**
	 * @param Meeting $meeting
	 * @return $this
	 */
	public function setMeeting(Meeting $meeting) {
		$this->meeting = $meeting;
		return $this;
	}

	/**
	 * @return Call
	 */
	public function getCall() {
		return $this->call;
	}

	/**
	 * @param Call $call
	 * @return $this
	 */
	public function setCall(Call $call) {
		$this->call = $call;
		return $this;
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