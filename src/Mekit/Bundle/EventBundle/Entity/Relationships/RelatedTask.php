<?php
namespace Mekit\Bundle\EventBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\TaskBundle\Entity\Task;

/**
 * @ORM\MappedSuperclass
 */
class RelatedTask extends RelatedCall {
	/**
	 * @var Task
	 *
	 * @ORM\OneToOne(targetEntity="Mekit\Bundle\TaskBundle\Entity\Task", mappedBy="event")
	 */
	protected $task;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return Task
	 */
	public function getTask() {
		return $this->task;
	}

	/**
	 * @param Task $task
	 * @return $this
	 */
	public function setTask(Task $task) {
		$this->task = $task;
		return $this;
	}

	/**
	 * @return bool|\Mekit\Bundle\CallBundle\Entity\Call|\Mekit\Bundle\MeetingBundle\Entity\Meeting|Task
	 */
	public function getBaseEntity() {
		if($this->task) {
			return $this->task;
		} else {
			return parent::getBaseEntity();
		}
	}
}