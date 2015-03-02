<?php
namespace Mekit\Bundle\CallBundle\Entity\Relationships;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\MappedSuperclass
 */
class RelatedUsers extends RelatedAccounts {
	/**
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinTable(name="mekit_rel_call_user")
	 * @ConfigField(
	 *      defaultValues={
	 *          "dataaudit"={
	 *              "auditable"=true
	 *          }
	 *      }
	 * )
	 */
	protected $users;

	public function __construct() {
		parent::__construct();
		$this->users = new ArrayCollection();
	}

	/**
	 * @return ArrayCollection
	 */
	public function getUsers() {
		return $this->users;
	}

	/**
	 * @param ArrayCollection $users
	 * @return $this
	 */
	public function setUsers($users) {
		$this->users->clear();
		foreach ($users as $user) {
			$this->addUser($user);
		}
		return $this;
	}

	/**
	 * @param User $user
	 * @return $this
	 */
	public function addUser(User $user) {
		if (!$this->users->contains($user)) {
			$this->users->add($user);
			//$user->addCall($this);
		}
		return $this;
	}

	/**
	 * @param User $user
	 * @return $this
	 */
	public function removeUser(User $user) {
		if ($this->users->contains($user)) {
			$this->users->removeElement($user);
			//$user->removeCall($this);
		}
		return $this;
	}

	/**
	 * @param User $user
	 * @return bool
	 */
	public function hasUser(User $user) {
		return $this->getUsers()->contains($user);
	}
}