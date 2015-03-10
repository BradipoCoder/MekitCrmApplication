<?php
namespace Mekit\Bundle\CallBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\CallBundle\Model\ExtendCall;
use Mekit\Bundle\EventBundle\Entity\EventInterface;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\CallBundle\Entity\Repository\CallRepository")
 * @ORM\Table(name="mekit_call", indexes={
 *      @ORM\Index(name="idx_call_name", columns={"name"}),
 *      @ORM\Index(name="idx_call_direction", columns={"direction"})
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
 *          }
 *      }
 * )
 */
class Call extends ExtendCall implements EventInterface
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
	 * @ORM\Column(name="name", type="string", length=255, nullable=false)
	 * @Oro\Versioned
	 */
	protected $name;

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
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getName();
	}
}