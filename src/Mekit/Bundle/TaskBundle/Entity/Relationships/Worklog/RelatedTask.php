<?php
namespace Mekit\Bundle\TaskBundle\Entity\Relationships\Worklog;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Mekit\Bundle\TaskBundle\Entity\Task;

/**
 * @ORM\MappedSuperclass
 */
class RelatedTask {
	/**
	 * @var Task
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\TaskBundle\Entity\Task", inversedBy="worklogs")
	 * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
	 * @ConfigField()
	 */
	protected $task;

	public function __construct() {
		//parent::__construct();
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
}