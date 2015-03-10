<?php
namespace Mekit\Bundle\EventBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\CallBundle\Entity\Call;

/**
 * @ORM\MappedSuperclass
 */
class RelatedCall extends RelatedMeeting {
	/**
	 * @var Call
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\CallBundle\Entity\Call", mappedBy="event")
	 */
	protected $call;

	public function __construct() {
		parent::__construct();
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
	 * @return bool|Call|\Mekit\Bundle\MeetingBundle\Entity\Meeting
	 */
	public function getBaseEntity() {
		if($this->call) {
			return $this->call;
		} else {
			return parent::getBaseEntity();
		}
	}
}