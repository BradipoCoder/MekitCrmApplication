<?php
namespace Mekit\Bundle\OpportunityBundle\Entity\Relationships\Opportunity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\MappedSuperclass
 */
class Ownership extends RelatedAccount
{
	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
	 * @Soap\ComplexType("string", nillable=true)
	 * @Oro\Versioned
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $owner;

	/**
	 * @var Organization
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
	 * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $organization;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return User
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param User $owningUser
	 * @return $this
	 */
	public function setOwner(User $owningUser) {
		$this->owner = $owningUser;

		return $this;
	}

	/**
	 * Get organization
	 *
	 * @return Organization
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * Set organization
	 *
	 * @param Organization $organization
	 * @return $this
	 */
	public function setOrganization(Organization $organization = null) {
		$this->organization = $organization;

		return $this;
	}
}