<?php
namespace Mekit\Bundle\EventBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\EventBundle\Model\ExtendEvent;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Entity()
 * @ORM\Table(name="mekit_event",
 *      indexes={
 *          @ORM\Index(name="idx_event_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_event_updated_at", columns={"updatedAt"}),
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
 *          "dataaudit"={
 *              "auditable"=true
 *          }
 *      }
 * )
 */
class Event extends ExtendEvent
{
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
	 * @ORM\JoinColumn(name="priority", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 */
	protected $priority;

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
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getBaseEntity()->getName();
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