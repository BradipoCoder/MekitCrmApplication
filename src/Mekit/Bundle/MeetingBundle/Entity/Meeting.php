<?php
namespace Mekit\Bundle\MeetingBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\EventBundle\Entity\EventInterface;
use Mekit\Bundle\MeetingBundle\Model\ExtendMeeting;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\MeetingBundle\Entity\Repository\MeetingRepository")
 * @ORM\Table(name="mekit_meeting", indexes={
 *      @ORM\Index(name="idx_meeting_name", columns={"name"})
 * })
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_meeting_index",
 *      routeView="mekit_meeting_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-group"
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
 *              "label"="mekit.meeting.entity_label",
 *              "icon"="icon-group",
 *              "view_route_name"="mekit_meeting_view",
 *              "edit_route_name"="mekit_meeting_edit"
 *          }
 *      }
 * )
 */
class Meeting extends ExtendMeeting implements EventInterface
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
	public function __toString() {
		return (string)$this->getName();
	}
}