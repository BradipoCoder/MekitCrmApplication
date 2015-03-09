<?php
namespace Mekit\Bundle\TaskBundle\Entity\Relationships\Task;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\CallBundle\Entity\Call;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\MappedSuperclass
 */
class RelatedCalls extends RelatedMeetings
{
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Mekit\Bundle\CallBundle\Entity\Call", inversedBy="tasks")
	 * @ORM\JoinTable(name="mekit_rel_task_call")
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $calls;


	public function __construct() {
		parent::__construct();
		$this->calls = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection
	 */
	public function getCalls() {
		return $this->calls;
	}

	/**
	 * @param ArrayCollection $calls
	 * @return $this
	 */
	public function setCalls($calls) {
		$this->calls->clear();
		foreach ($calls as $call) {
			$this->addCall($call);
		}

		return $this;
	}

	/**
	 * @param Call $call
	 * @return $this
	 */
	public function addCall(Call $call) {
		if (!$this->calls->contains($call)) {
			$this->calls->add($call);
			$call->addTask($this);
		}

		return $this;
	}

	/**
	 * @param Call $call
	 * @return $this
	 */
	public function removeCall(Call $call) {
		if ($this->calls->contains($call)) {
			$this->calls->removeElement($call);
			$call->removeTask($this);
		}

		return $this;
	}

	/**
	 * @param Call $call
	 * @return bool
	 */
	public function hasCall(Call $call) {
		return $this->getCalls()->contains($call);
	}
}