<?php

namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

class AccountTest extends \PHPUnit_Framework_TestCase {


	/**
	 * @dataProvider provider
	 * @param string $property
	 */
	public function testSettersAndGetters($property, $value) {
		$obj = new Account();
		call_user_func_array(array($obj, 'set' . ucfirst($property)), array($value));
		$this->assertEquals($value, call_user_func_array(array($obj, 'get' . ucfirst($property)), array()));
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function provider() {
		return array(
			array('id', '123'),
			array('name', 'MEKIT'),
			array('owner', new User()),
			array('organization', new Organization()),
			array('createdAt', new \DateTime()),
			array('updatedAt', new \DateTime()),
			array('tags', new ArrayCollection())
		);
	}

	public function testToString() {
		$obj = new Account();
		$obj->setName('name');
		$this->assertEquals('name', $obj->__toString());
	}

}