<?php
namespace Mekit\Bundle\CallBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Mekit\Bundle\ProjectBundle\Entity\Project;

/**
 * @ORM\MappedSuperclass
 */
class RelatedProjects {
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Mekit\Bundle\ProjectBundle\Entity\Project", mappedBy="calls")
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $projects;

	public function __construct() {
		//parent::__construct();
		$this->projects = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection
	 */
	public function getProjects() {
		return $this->projects;
	}

	/**
	 * @param ArrayCollection $projects
	 * @return $this
	 */
	public function setProjects($projects) {
		$this->projects->clear();
		foreach ($projects as $project) {
			$this->addProject($project);
		}
		return $this;
	}

	/**
	 * @param Project $project
	 * @return $this
	 */
	public function addProject(Project $project) {
		if (!$this->projects->contains($project)) {
			$this->projects->add($project);
			$project->addCall($this);
		}
		return $this;
	}

	/**
	 * @param Project $project
	 * @return $this
	 */
	public function removeProject(Project $project) {
		if ($this->projects->contains($project)) {
			$this->projects->removeElement($project);
			$project->removeCall($this);
		}
		return $this;
	}

	/**
	 * @param Project $project
	 * @return bool
	 */
	public function hasProject(Project $project) {
		return $this->getProjects()->contains($project);
	}
}