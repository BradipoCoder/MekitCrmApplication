<?php

namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\AccountBundle\Entity\AccountAddress;
use Mekit\Bundle\ContactBundle\Entity\ContactEmail;
use Mekit\Bundle\ContactBundle\Entity\ContactPhone;
use Mekit\Bundle\TestBundle\Tests\Helpers\MekitEntityTests;
use Oro\Bundle\AddressBundle\Entity\AddressType;
use Oro\Bundle\AddressBundle\Entity\Country;


class AccountTest extends MekitEntityTests {

	protected $entityName = 'Mekit\Bundle\AccountBundle\Entity\Account';

	public function testEmails() {
		$emailOne = new ContactEmail('one@test.com');
		$emailTwo = new ContactEmail('two@test.com');
		$emailThree = new ContactEmail('three@test.com');
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

		$email = new ContactEmail('email@example.com');
		$entity->addEmail($email);
		$this->assertNull($entity->getPrimaryEmail());

		$entity->setPrimaryEmail($email);
		$this->assertSame($email, $entity->getPrimaryEmail());

		$email2 = new ContactEmail('new@example.com');
		$entity->addEmail($email2);
		$entity->setPrimaryEmail($email2);

		$this->assertSame($email2, $entity->getPrimaryEmail());
		$this->assertFalse($email->isPrimary());
	}

	public function testPhones() {
		$phoneOne = new ContactPhone('06001122334455');
		$phoneTwo = new ContactPhone('07001122334455');
		$phoneThree = new ContactPhone('08001122334455');
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

		$phone = new ContactPhone('06001122334455');
		$entity->addPhone($phone);
		$this->assertNull($entity->getPrimaryPhone());

		$entity->setPrimaryPhone($phone);
		$this->assertSame($phone, $entity->getPrimaryPhone());

		$phone2 = new ContactPhone('22001122334455');
		$entity->addPhone($phone2);
		$entity->setPrimaryPhone($phone2);

		$this->assertSame($phone2, $entity->getPrimaryPhone());
		$this->assertFalse($phone->isPrimary());
	}

	public function testAddresses() {
		$addressOne = new AccountAddress();
		$addressOne->setCountry(new Country('US'));
		$addressTwo = new AccountAddress();
		$addressTwo->setCountry(new Country('UK'));
		$addressThree = new AccountAddress();
		$addressThree->setCountry(new Country('RU'));
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

		$address = new AccountAddress();
		$entity->addAddress($address);
		$this->assertNull($entity->getPrimaryAddress());

		$address->setPrimary(true);
		$this->assertSame($address, $entity->getPrimaryAddress());

		$newPrimary = new AccountAddress();
		$entity->addAddress($newPrimary);

		$entity->setPrimaryAddress($newPrimary);
		$this->assertSame($newPrimary, $entity->getPrimaryAddress());

		$this->assertFalse($address->isPrimary());
	}

	public function testGetAddressByTypeName() {
		$entity = new Account();
		$this->assertNull($entity->getAddressByTypeName('billing'));

		$address = new AccountAddress();
		$address->addType(new AddressType('billing'));
		$entity->addAddress($address);

		$this->assertSame($address, $entity->getAddressByTypeName('billing'));
	}

	public function testGetAddressByType() {
		$address = new AccountAddress();
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
		$addressOne = new AccountAddress();
		$addressOne->addType($shippingType);
		$entity->addAddress($addressOne);

		$addressTwo = new AccountAddress();
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
		$email = new ContactEmail();

		$entity = new Account();
		$this->assertFalse($entity->hasEmail($email));

		$entity->addEmail($email);
		$this->assertTrue($entity->hasEmail($email));
	}

	public function testHasPhone() {
		$phone = new ContactPhone();

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