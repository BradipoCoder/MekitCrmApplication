<?php
namespace Mekit\Bundle\ListBundle\Tests\Functional\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\ListItem;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Mekit\Bundle\AccountBundle\Entity\Account;

class LoadListBundleFixtures extends AbstractFixture
{
	/** @var ObjectManager */
	protected $em;

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$this->em = $manager;

		//assuming that there is at least a list and an item created during installation
		$listGroup = $manager->getRepository('MekitListBundle:ListGroup')->findOneBy(["id"=>1]);
		$listItem = $manager->getRepository('MekitListBundle:ListItem')->findOneBy(["id"=>1]);

		$this->setReference('default_listgroup', $listGroup);
		$this->setReference('default_listitem', $listItem);
	}
}



