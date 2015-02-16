<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity;

use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;
use Mekit\Bundle\ContactInfoBundle\Entity\Email;
use Mekit\Bundle\ContactInfoBundle\Entity\Phone;
use Oro\Bundle\AddressBundle\Entity\AddressType;
use Oro\Bundle\AddressBundle\Entity\Country;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;


class AccountTest extends MekitUnitEntityTest {
	/** @var string  */
	protected $entityName = 'Mekit\Bundle\AccountBundle\Entity\Account';


	public function testEmails() {
		$emailOne = new Email('one@test.com');
		$emailTwo = new Email('two@test.com');
		$emailThree = new Email('three@test.com');
		$emails = array($emailOne, $emailTwo);

		$entity = new Account();
		$this->assertSame($entity, $entity->resetEmails($emails));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($emails, $actual->toArray());

		$this->assertSame($entity, $entity->addEmail($emailTwo));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($emails, $actual->toArray());

		$this->assertSame($entity, $entity->addEmail($emailThree));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($emailOne, $emailTwo, $emailThree), $actual->toArray());

		$this->assertSame($entity, $entity->removeEmail($emailOne));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $emailTwo, 2 => $emailThree), $actual->toArray());

		$this->assertSame($entity, $entity->removeEmail($emailOne));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $emailTwo, 2 => $emailThree), $actual->toArray());
	}

	public function testGetPrimaryEmail() {
		$entity = new Account();
		$this->assertNull($entity->getPrimaryEmail());

		$email = new Email('email@example.com');
		$entity->addEmail($email);
		$this->assertNull($entity->getPrimaryEmail());

		$entity->setPrimaryEmail($email);
		$this->assertSame($email, $entity->getPrimaryEmail());

		$email2 = new Email('new@example.com');
		$entity->addEmail($email2);
		$entity->setPrimaryEmail($email2);

		$this->assertSame($email2, $entity->getPrimaryEmail());
		$this->assertFalse($email->isPrimary());
	}

	public function testPhones() {
		$phoneOne = new Phone('06001122334455');
		$phoneTwo = new Phone('07001122334455');
		$phoneThree = new Phone('08001122334455');
		$phones = array($phoneOne, $phoneTwo);

		$entity = new Account();
		$this->assertSame($entity, $entity->resetPhones($phones));
		$actual = $entity->getPhones();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($phones, $actual->toArray());

		$this->assertSame($entity, $entity->addPhone($phoneTwo));
		$actual = $entity->getPhones();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($phones, $actual->toArray());

		$this->assertSame($entity, $entity->addPhone($phoneThree));
		$actual = $entity->getPhones();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($phoneOne, $phoneTwo, $phoneThree), $actual->toArray());

		$this->assertSame($entity, $entity->removePhone($phoneOne));
		$actual = $entity->getPhones();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $phoneTwo, 2 => $phoneThree), $actual->toArray());

		$this->assertSame($entity, $entity->removePhone($phoneOne));
		$actual = $entity->getPhones();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $phoneTwo, 2 => $phoneThree), $actual->toArray());
	}

	public function testGetPrimaryPhone() {
		$entity = new Account();
		$this->assertNull($entity->getPrimaryPhone());

		$phone = new Phone('06001122334455');
		$entity->addPhone($phone);
		$this->assertNull($entity->getPrimaryPhone());

		$entity->setPrimaryPhone($phone);
		$this->assertSame($phone, $entity->getPrimaryPhone());

		$phone2 = new Phone('22001122334455');
		$entity->addPhone($phone2);
		$entity->setPrimaryPhone($phone2);

		$this->assertSame($phone2, $entity->getPrimaryPhone());
		$this->assertFalse($phone->isPrimary());
	}

	public function testAddresses() {
		$addressOne = new Address();
		$addressOne->setCountry(new Country('US'));
		$addressTwo = new Address();
		$addressTwo->setCountry(new Country('UK'));
		$addressThree = new Address();
		$addressThree->setCountry(new Country('IT'));
		$addresses = array($addressOne, $addressTwo);

		$entity = new Account();
		$this->assertSame($entity, $entity->resetAddresses($addresses));
		$actual = $entity->getAddresses();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($addresses, $actual->toArray());

		$this->assertSame($entity, $entity->addAddress($addressTwo));
		$actual = $entity->getAddresses();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($addresses, $actual->toArray());

		$this->assertSame($entity, $entity->addAddress($addressThree));
		$actual = $entity->getAddresses();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($addressOne, $addressTwo, $addressThree), $actual->toArray());

		$this->assertSame($entity, $entity->removeAddress($addressOne));
		$actual = $entity->getAddresses();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $addressTwo, 2 => $addressThree), $actual->toArray());

		$this->assertSame($entity, $entity->removeAddress($addressOne));
		$actual = $entity->getAddresses();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $addressTwo, 2 => $addressThree), $actual->toArray());
	}

	public function testGetPrimaryAddress() {
		$entity = new Account();
		$this->assertNull($entity->getPrimaryAddress());

		$address = new Address();
		$entity->addAddress($address);
		$this->assertNull($entity->getPrimaryAddress());

		$address->setPrimary(true);
		$this->assertSame($address, $entity->getPrimaryAddress());

		$newPrimary = new Address();
		$entity->addAddress($newPrimary);

		$entity->setPrimaryAddress($newPrimary);
		$this->assertSame($newPrimary, $entity->getPrimaryAddress());

		$this->assertFalse($address->isPrimary());
	}

	public function testGetAddressByTypeName() {
		$entity = new Account();
		$this->assertNull($entity->getAddressByTypeName('billing'));

		$address = new Address();
		$address->addType(new AddressType('billing'));
		$entity->addAddress($address);

		$this->assertSame($address, $entity->getAddressByTypeName('billing'));
	}

	public function testGetAddressByType() {
		$address = new Address();
		$addressType = new AddressType('billing');
		$address->addType($addressType);

		$entity = new Account();
		$this->assertNull($entity->getAddressByType($addressType));

		$entity->addAddress($address);
		$this->assertSame($address, $entity->getAddressByType($addressType));
	}

	public function testSetAddressType() {
		$entity = new Account();

		$shippingType = new AddressType('shipping');
		$addressOne = new Address();
		$addressOne->addType($shippingType);
		$entity->addAddress($addressOne);

		$addressTwo = new Address();
		$entity->addAddress($addressTwo);

		$entity->setAddressType($addressTwo, $shippingType);
		$this->assertFalse($addressOne->hasTypeWithName('shipping'));
		$this->assertTrue($addressTwo->hasTypeWithName('shipping'));
	}


	public function testGetTags() {
		$entity = new Account();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $entity->getTags());
		$this->assertTrue($entity->getTags()->isEmpty());

		$entity->setTags(array('tag1', 'tag2'));
		$this->assertEquals(array('tag1', 'tag2'), $entity->getTags());
	}

	public function testHasEmail() {
		$email = new Email();

		$entity = new Account();
		$this->assertFalse($entity->hasEmail($email));

		$entity->addEmail($email);
		$this->assertTrue($entity->hasEmail($email));
	}

	public function testHasPhone() {
		$phone = new Phone();

		$entity = new Account();
		$this->assertFalse($entity->hasPhone($phone));

		$entity->addPhone($phone);
		$this->assertTrue($entity->hasPhone($phone));
	}

	/**
	 * Data provider for simple get/set tests executed in MekitEntityTests::testSettersAndGetters(prop, value, expected)
	 * Properties must follow getter/setter naming convention
	 *
	 * @return array
	 */
	public function entityPropertyProvider() {
		$now = new \DateTime('now');
		$listItem = new ListItem();//$this->getMock('Mekit\Bundle\ListBundle\Entity\ListItem');
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
			array('type', $listItem),
			array('state', $listItem),
			array('industry', $listItem),
			array('source', $listItem),
			array('owner', $user),
			array('organization', $organization),
			array('createdAt', $now),
			array('updatedAt', $now)
		);
	}
}