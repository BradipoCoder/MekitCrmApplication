<?php
namespace Mekit\Bundle\OpportunityBundle\Entity\Relationships\Opportunity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\MappedSuperclass
 */
class RelatedAccount extends ListItems
{
	/**
	 * @var Account
	 * @ORM\ManyToOne(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account")
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="SET NULL")
	 * @ConfigField()
	 */
	protected $account;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return Account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param Account $account
	 * @return $this
	 */
	public function setAccount($account) {
		$this->account = $account;

		return $this;
	}
}