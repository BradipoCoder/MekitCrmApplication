<?php
namespace Mekit\Bundle\AccountBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Mekit\Bundle\ListBundle\Entity\ListItem;

/**
 * @ORM\MappedSuperclass
 */
class ListItems extends RelatedContacts {
	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=false)
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

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="state", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=91,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $state;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="industry", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=92,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $industry;

	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="source", referencedColumnName="id", nullable=false)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          },
	 *          "importexport"={
	 *              "order"=93,
	 *              "short"=true
	 *          }
	 *      }
	 * )
	 */
	protected $source;

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

	/**
	 * @return ListItem
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param ListItem $state
	 * @return $this
	 */
	public function setState($state) {
		$this->state = $state;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getIndustry() {
		return $this->industry;
	}

	/**
	 * @param ListItem $industry
	 * @return $this
	 */
	public function setIndustry($industry) {
		$this->industry = $industry;
		return $this;
	}

	/**
	 * @return ListItem
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param ListItem $source
	 * @return $this
	 */
	public function setSource($source) {
		$this->source = $source;
		return $this;
	}

}