<?php
namespace Mekit\Bundle\CallBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Mekit\Bundle\TaskBundle\Entity\Task;

/**
 * @ORM\MappedSuperclass
 */
class RelatedTasks extends RelatedMeetings {
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Mekit\Bundle\TaskBundle\Entity\Task", mappedBy="calls")
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $tasks;

	public function __construct() {
		parent::__construct();
		$this->tasks = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection
	 */
	public function getTasks() {
		return $this->tasks;
	}

	/**
	 * @param ArrayCollection $tasks
	 * @return $this
	 */
	public function setTasks($tasks) {
		$this->tasks->clear();
		foreach ($tasks as $task) {
			$this->addTask($task);
		}
		return $this;
	}

	/**
	 * @param Task $task
	 * @return $this
	 */
	public function addTask(Task $task) {
		if (!$this->tasks->contains($task)) {
			$this->tasks->add($task);
			$task->addCall($this);
		}
		return $this;
	}

	/**
	 * @param Task $task
	 * @return $this
	 */
	public function removeTask(Task $task) {
		if ($this->tasks->contains($task)) {
			$this->tasks->removeElement($task);
			$task->removeCall($this);
		}
		return $this;
	}

	/**
	 * @param Task $task
	 * @return bool
	 */
	public function hasTask(Task $task) {
		return $this->getTasks()->contains($task);
	}
}