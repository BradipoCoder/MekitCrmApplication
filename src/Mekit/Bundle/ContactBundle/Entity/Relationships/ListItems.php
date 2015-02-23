<?php
namespace Mekit\Bundle\ContactBundle\Entity\Relationships;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Mekit\Bundle\ListBundle\Entity\ListItem;

/**
 * @ORM\MappedSuperclass
 */
class ListItems extends RelatedAccounts {
	/**
	 * @var ListItem
	 *
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\ListBundle\Entity\ListItem")
	 * @ORM\JoinColumn(name="job_title", referencedColumnName="id", nullable=true)
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
	protected $jobTitle;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return ListItem
	 */
	public function getJobTitle() {
		return $this->jobTitle;
	}

	/**
	 * @param ListItem $jobTitle
	 * @return $this
	 */
	public function setJobTitle($jobTitle) {
		$this->jobTitle = $jobTitle;
		return $this;
	}

}