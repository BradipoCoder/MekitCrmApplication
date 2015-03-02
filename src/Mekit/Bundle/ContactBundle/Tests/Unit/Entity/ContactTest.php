<?php
namespace Mekit\Bundle\ContactBundle\Tests\Unit\Entity;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Mekit\Bundle\ContactBundle\Entity\Contact;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

class ContactTest extends MekitUnitEntityTest {
	/** @var string */
	protected $entityName = 'Mekit\Bundle\ContactBundle\Entity\Contact';

	public function testToStringMethod() {
		$this->hasToStringMethod($this->entityName);
		$entity = new Contact();

		$prefix = "Mr";
		$first = "First";
		$middle = "Middle";
		$last = "Last";
		$suffix = "My Friend";

		$entity->setNamePrefix($prefix);
		$this->assertEquals($this->getFullName($prefix), $entity->__toString());
		$entity->setFirstName($first);
		$this->assertEquals($this->getFullName($prefix, $first), $entity->__toString());
		$entity->setMiddleName($middle);
		$this->assertEquals($this->getFullName($prefix, $first, $middle), $entity->__toString());
		$entity->setLastName($last);
		$this->assertEquals($this->getFullName($prefix, $first, $middle, $last), $entity->__toString());
		$entity->setNameSuffix($suffix);
		$this->assertEquals($this->getFullName($prefix, $first, $middle, $last, $suffix), $entity->__toString());
	}

	public function testTags() {
		$entity = new Contact();
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
			array('namePrefix', 'Prefix'),
			array('firstName', 'First'),
			array('middleName', 'Middle'),
			array('lastName', 'Last'),
			array('nameSuffix', 'Suffix'),
			array('gender', 'male'),
			array('birthday', $now),
			array('description', 'Some description'),
			array('skype', 'Skype'),
			array('twitter', 'Twitter'),
			array('facebook', 'Facebook'),
			array('googlePlus', 'Google+'),
			array('linkedIn', 'LinkedIn'),
			array('owner', $user),
			array('organization', $organization),
			array('createdAt', $now),
			array('updatedAt', $now)
		);
	}

	public function testPrePersist() {
		$entity = new Contact();
		$this->assertNull($entity->getCreatedAt());
		$this->assertNull($entity->getUpdatedAt());
		$entity->doPrePersist();
		$this->assertInstanceOf('\DateTime', $entity->getCreatedAt());
		$this->assertInstanceOf('\DateTime', $entity->getUpdatedAt());
	}

	public function testPreUpdate() {
		$entity = new Contact();
		$this->assertNull($entity->getUpdatedAt());
		$entity->doPreUpdate();
		$this->assertInstanceOf('\DateTime', $entity->getUpdatedAt());
	}

	/**
	 * @param string $prefix
	 * @param string $first
	 * @param string $middle
	 * @param string $last
	 * @param string $suffix
	 * @return string
	 */
	private function getFullName($prefix="", $first="", $middle="", $last="", $suffix="") {
		$name = $prefix . ' '
			. $first . ' '
			. $middle . ' '
			. $last . ' '
			. $suffix;
		$name = preg_replace('/ +/', ' ', $name);
		return (string)trim($name);
	}
}