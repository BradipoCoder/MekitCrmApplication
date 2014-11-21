<?php

namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity;

use Mekit\Bundle\TestBundle\Tests\Helpers\MekitEntityTests;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ListBundle\Entity\ListItem;

class AccountTest extends MekitEntityTests {

	protected $entityName = 'Mekit\Bundle\AccountBundle\Entity\Account';



	/**
	 * Data provider for simple get/set tests executed in MekitEntityTests::testSettersAndGetters(prop, value, expected)
	 * Properties must follow getter/setter naming convention
	 *
	 * @return array
	 */
	public function propertyTestsProvider() {
		$now = new \DateTime('now');
		$listItem = $this->getMock('Mekit\Bundle\ListBundle\Entity\ListItem');
		$user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
		$organization = $this->getMock('Oro\Bundle\OrganizationBundle\Entity\Organization');

		return array(
			array('id', '123'),
			array('name', 'MEKIT'),
			array('vatid', '123'),
			array('nin', '123'),
			array('website', 'www.test.com'),
			array('fax', '123'),
			array('description', 'test'),
			array('type', $listItem),
			array('state', $listItem),
			array('industry', $listItem),
			array('source', $listItem),
			array('assignedTo', $user),
			array('owner', $user),
			array('organization', $organization),
			array('createdAt', $now),
			array('updatedAt', $now)
		);
	}
}