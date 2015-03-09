<?php
namespace Mekit\Bundle\ContactBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Mekit\Bundle\TaskBundle\Entity\Task;

/**
 * @ORM\MappedSuperclass
 */
class RelatedTasks extends RelatedCalls {
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Mekit\Bundle\TaskBundle\Entity\Task", mappedBy="contacts")
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
			$task->addContact($this);
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
			$task->removeContact($this);
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