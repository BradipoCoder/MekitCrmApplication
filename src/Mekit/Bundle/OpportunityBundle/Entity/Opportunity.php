<?php
namespace Mekit\Bundle\OpportunityBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;

use Mekit\Bundle\OpportunityBundle\Model\ExtendOpportunity;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *      name="mekit_opportunity",
 *      indexes={
 *          @ORM\Index(name="idx_opportunity_owner", columns={"owner_id"}),
 *          @ORM\Index(name="idx_opportunity_organization", columns={"organization_id"}),
 *          @ORM\Index(name="idx_opportunity_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_opportunity_updated_at", columns={"updatedAt"}),
 *          @ORM\Index(name="idx_opportunity_name", columns={"name"}),
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_opportunity_index",
 *      routeView="mekit_opportunity_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-money"
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
class Opportunity extends ExtendOpportunity
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
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=255)
	 * @Soap\ComplexType("string")
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="text", length=65535, nullable=true)
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 */
	protected $description;

	/**
	 * @var double
	 *
	 * @ORM\Column(type="money", nullable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *  defaultValues={
	 *      "dataaudit"={
	 *          "auditable"=true
	 *      }
	 *  }
	 * )
	 */
	protected $amount;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="percent", nullable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *  defaultValues={
	 *      "dataaudit"={
	 *          "auditable"=true
	 *      }
	 *  }
	 * )
	 */
	protected $probability;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(type="datetime")
	 * @Soap\ComplexType("dateTime", nillable=true)
	 * @ConfigField(
	 *      defaultValues={
	 *          "entity"={
	 *              "label"="oro.ui.created_at"
	 *          },
	 *          "importexport"={
	 *              "excluded"=true
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
	 *          },
	 *          "importexport"={
	 *              "excluded"=true
	 *          }
	 *      }
	 * )
	 */
	protected $updatedAt;

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
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * @param float $amount
	 * @return $this
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getProbability() {
		return $this->probability;
	}

	/**
	 * @param float $probability
	 * @return $this
	 */
	public function setProbability($probability) {
		$this->probability = $probability;
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
		return (string)$this->getName();
	}

	/**
	 * Pre persist event listener
	 *
	 * @ORM\PrePersist
	 */
	public function doPrePersist() {
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