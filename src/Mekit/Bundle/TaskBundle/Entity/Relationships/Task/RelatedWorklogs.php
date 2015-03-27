<?php
namespace Mekit\Bundle\TaskBundle\Entity\Relationships\Task;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\WorklogBundle\Entity\Worklog;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\MappedSuperclass
 */
class RelatedWorklogs
{
	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\WorklogBundle\Entity\Worklog", mappedBy="task")
	 * @ConfigField()
	 */
	protected $worklogs;

	public function __construct() {
		//parent::__construct();
		$this->worklogs = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection
	 */
	public function getWorklogs() {
		return $this->worklogs;
	}

	/**
	 * @param ArrayCollection $worklogs
	 * @return $this
	 */
	public function setProjects($worklogs) {
		$this->worklogs = $worklogs;
		/** @var Worklog $worklog */
		foreach ($worklogs as $worklog) {
			$worklog->setTask($this);
		}

		return $this;
	}

}