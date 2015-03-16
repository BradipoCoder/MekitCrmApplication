<?php
namespace Mekit\Bundle\TaskBundle\Entity\Relationships\Task;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\MappedSuperclass
 */
class ListItems extends RelatedProject
{
	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=90,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $type;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return ListItem
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param ListItem $type
	 * @return $this
	 */
	public function setType($type) {
		$this->type = $type;

		return $this;
	}
}