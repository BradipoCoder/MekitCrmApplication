<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Mekit\Bundle\AccountBundle\Entity\Account;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

class AccountTest extends MekitUnitEntityTest {
	/** @var string */
	protected $entityName = 'Mekit\Bundle\AccountBundle\Entity\Account';

	public function testToStringMethod() {
		$this->hasToStringMethod($this->entityName);
		$entity = new Account();
		$entity->setName("Test");
		$this->assertEquals("Test", $entity->__toString());
	}

	public function testTags() {
		$entity = new Account();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $entity->getTags());
		$this->assertTrue($entity->getTags()->isEmpty());
		$entity->setTags(array('tag1', 'tag2'));
		$this->assertEquals(array('tag1', 'tag2'), $entity->getTags());
		//
		$entity->setId(1000);
		$this->assertEquals(1000, $entity->getTaggableId());
	}

	/**
	 * @dataProvider entityPropertyProvider
	 * @param string $property
	 * @param mixed $value
	 * @param mixed $expected
	 */
	public function testSettersAndGetters($property, $value, $expected=null) {
		$this->checkGetterSetterMethods($this->entityName, $property, $value, $expected);
	}

	/**
	 * Data provider for simple get/set tests executed in MekitEntityTests::testSettersAndGetters(prop, value, expected)
	 * Properties must follow getter/setter naming convention
	 *
	 * @return array
	 */
	public function entityPropertyProvider() {
		$now = new \DateTime('now');
		$user = new User();
		$organization = new Organization();

		return array(
			array('id', '123'),
			array('name', 'MEKIT'),
			array('vatid', '123'),
			array('nin', '123'),
			array('website', 'www.test.com'),
			array('fax', '123'),
			array('description', 'test'),
			array('owner', $user),
			array('organization', $organization),
			array('createdAt', $now),
			array('updatedAt', $now)
		);
	}

	public function testPrePersist() {
		$entity = new Account();
		$this->assertNull($entity->getCreatedAt());
		$this->assertNull($entity->getUpdatedAt());
		$entity->doPrePersist();
		$this->assertInstanceOf('\DateTime', $entity->getCreatedAt());
		$this->assertInstanceOf('\DateTime', $entity->getUpdatedAt());
	}

	public function testPreUpdate() {
		$entity = new Account();
		$this->assertNull($entity->getUpdatedAt());
		$entity->doPreUpdate();
		$this->assertInstanceOf('\DateTime', $entity->getUpdatedAt());
	}
}