<?php
namespace Mekit\Bundle\TaskBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Mekit\Bundle\MeetingBundle\Entity\Meeting;

/**
 * @ORM\MappedSuperclass
 */
class RelatedMeetings {
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Mekit\Bundle\MeetingBundle\Entity\Meeting", inversedBy="tasks")
	 * @ORM\JoinTable(name="mekit_rel_task_meeting")
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $meetings;


	public function __construct() {
		//parent::__construct();
		$this->meetings = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection
	 */
	public function getMeetings() {
		return $this->meetings;
	}

	/**
	 * @param ArrayCollection $meetings
	 * @return $this
	 */
	public function setMeetings($meetings) {
		$this->meetings->clear();
		foreach ($meetings as $meeting) {
			$this->addMeeting($meeting);
		}
		return $this;
	}

	/**
	 * @param Meeting $meeting
	 * @return $this
	 */
	public function addMeeting(Meeting $meeting) {
		if (!$this->meetings->contains($meeting)) {
			$this->meetings->add($meeting);
			$meeting->addTask($this);
		}
		return $this;
	}

	/**
	 * @param Meeting $meeting
	 * @return $this
	 */
	public function removeMeeting(Meeting $meeting) {
		if ($this->meetings->contains($meeting)) {
			$this->meetings->removeElement($meeting);
			$meeting->removeTask($this);
		}
		return $this;
	}

	/**
	 * @param Meeting $meeting
	 * @return bool
	 */
	public function hasMeeting(Meeting $meeting) {
		return $this->getMeetings()->contains($meeting);
	}
}