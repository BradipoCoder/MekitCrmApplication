<?php
namespace Mekit\Bundle\ContactBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\MappedSuperclass
 */
class RelatedMeetings
{
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Mekit\Bundle\MeetingBundle\Entity\Meeting", mappedBy="contacts")
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
	 * @param Meeting $meeting
	 * @return $this
	 */
	public function removeMeeting(Meeting $meeting) {
		if ($this->meetings->contains($meeting)) {
			$this->meetings->removeElement($meeting);
			$meeting->removeContact($this);
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
			$meeting->addContact($this);
		}

		return $this;
	}
}