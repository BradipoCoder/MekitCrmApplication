<?php
namespace Mekit\Bundle\CallBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Mekit\Bundle\EventBundle\Entity\Event;

/**
 * @ORM\MappedSuperclass
 */
class RelatedEvent extends RelatedAccounts {
	/**
	 * @var Event
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\EventBundle\Entity\Event", inversedBy="call", fetch="EAGER", cascade={"persist","remove"})
	 * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
	 * @Soap\ComplexType("Mekit\Bundle\EventBundle\Entity\Event", nillable=false)
	 * @ConfigField(
	 *      defaultValues={}
	 * )
	 */
	protected $event;

	public function __construct() {
		parent::__construct();
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
}