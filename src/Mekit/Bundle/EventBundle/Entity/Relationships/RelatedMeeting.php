<?php
namespace Mekit\Bundle\EventBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;

/**
 * @ORM\MappedSuperclass
 */
class RelatedMeeting {
	/**
	 * @var Meeting
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\MeetingBundle\Entity\Meeting", mappedBy="event")
	 */
	protected $meeting;

	public function __construct() {
		//parent::__construct();
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
	 * @return bool|Meeting
	 */
	public function getBaseEntity() {
		if($this->meeting) {
			return $this->meeting;
		} else {
			return false;
		}
	}
}