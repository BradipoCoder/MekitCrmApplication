<?php
namespace Mekit\Bundle\ProjectBundle\Tests\Functional\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Mekit\Bundle\AccountBundle\Entity\Account;

class LoadProjectBundleFixtures extends AbstractFixture implements ContainerAwareInterface
{
	const ACCOUNT_NAME  = 'Mekit';

	/** @var ObjectManager */
	protected $em;

	/** @var User */
	protected $user;

	/** @var Organization */
	protected $organization;

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		//
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$this->em = $manager;
		$this->organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

		$this->createAccount();
	}

	protected function createAccount()
	{
		$account = new Account();
		$account->setName(self::ACCOUNT_NAME);
		$account->setOrganization($this->organization);
		$account->setOwner($this->getUser());

		$this->em->persist($account);
		$this->em->flush();

		$this->setReference('default_account', $account);

		return $this;
	}

	/**
	 * @return User
	 */
	protected function getUser()
	{
		if (empty($this->user)) {
			$this->user = $this->em->getRepository('OroUserBundle:User')->findOneBy(['username' => 'admin']);
		}

		return $this->user;
	}
}



