<?php
namespace Mekit\Bundle\AccountBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Mekit\Bundle\ProjectBundle\Entity\Project;

/**
 * @ORM\MappedSuperclass
 */
class RelatedProjects {
	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Mekit\Bundle\ProjectBundle\Entity\Project", mappedBy="account")
	 * @ConfigField()
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
		/** @var Project $project */
		foreach ($projects as $project) {
			$project->setAccount($this);
		}
		return $this;
	}

}