<?php
namespace Mekit\Bundle\WorklogBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\WorklogBundle\Model\ExtendWorklog;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\WorklogBundle\Entity\Repository\WorklogRepository")
 * @ORM\Table(name="mekit_worklog", indexes={
 *      @ORM\Index(name="idx_worklog_exec_date", columns={"execution_date"})
 * })
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-ok-sign"
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
class Worklog extends ExtendWorklog
{
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
	 * @var \DateTime
	 *
	 * @ORM\Column(name="execution_date", type="datetime", nullable=false)
	 * @Oro\Versioned
	 */
	protected $executionDate;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="duration", type="integer", nullable=false)
	 * @Oro\Versioned
	 */
	protected $duration;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", length=65535, nullable=false)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 */
	protected $description;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Oro\Versioned
	 */
	protected $owner;

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
	 * @return \DateTime
	 */
	public function getExecutionDate() {
		return $this->executionDate;
	}

	/**
	 * @param \DateTime $executionDate
	 * @return $this
	 */
	public function setExecutionDate($executionDate) {
		$this->executionDate = $executionDate;

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
	 * @return User
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param User $owner
	 * @return $this
	 */
	public function setOwner($owner) {
		$this->owner = $owner;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param \DateTime $createdAt
	 * @return $this
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	/**
	 * @param \DateTime $updatedAt
	 * @return $this
	 */
	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return trim(substr($this->getDescription(), 0, 32));
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