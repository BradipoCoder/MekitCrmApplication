<?php
namespace Mekit\Bundle\TaskBundle\Entity\Relationships\Task;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\ProjectBundle\Entity\Project;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\MappedSuperclass
 */
class RelatedProject extends RelatedWorklogs
{
	/**
	 * @var Project
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ProjectBundle\Entity\Project", inversedBy="tasks")
	 * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
	 * @ConfigField()
	 */
	protected $project;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return Project
	 */
	public function getProject() {
		return $this->project;
	}

	/**
	 * @param Project $project
	 * @return $this
	 */
	public function setProject(Project $project) {
		$this->project = $project;

		return $this;
	}
}