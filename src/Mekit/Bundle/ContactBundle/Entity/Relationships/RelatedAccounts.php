<?php
namespace Mekit\Bundle\ContactBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Mekit\Bundle\AccountBundle\Entity\Account;

/**
 * @ORM\MappedSuperclass
 */
class RelatedAccounts extends RelatedTasks {
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Mekit\Bundle\AccountBundle\Entity\Account", mappedBy="contacts")
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $accounts;

	public function __construct() {
		parent::__construct();
		$this->accounts = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection
	 */
	public function getAccounts() {
		return $this->accounts;
	}

	/**
	 * @param ArrayCollection $accounts
	 * @return $this
	 */
	public function setAccounts($accounts) {
		$this->accounts->clear();
		foreach ($accounts as $account) {
			$this->addAccount($account);
		}
		return $this;
	}

	/**
	 * @param Account $account
	 * @return $this
	 */
	public function addAccount(Account $account) {
		if (!$this->accounts->contains($account)) {
			$this->accounts->add($account);
			$account->addContact($this);
		}
		return $this;
	}

	/**
	 * @param Account $account
	 * @return $this
	 */
	public function removeAccount(Account $account) {
		if ($this->accounts->contains($account)) {
			$this->accounts->removeElement($account);
			$account->removeContact($this);
		}
		return $this;
	}

	/**
	 * @param Account $account
	 * @return bool
	 */
	public function hasAccount(Account $account) {
		return $this->getAccounts()->contains($account);
	}
}