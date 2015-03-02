<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactInfoBundle\Entity\Phone;

class PhonesTest extends MekitUnitEntityTest {

	public function testHasPhone() {
		$phone = new Phone();
		$entity = new Account();
		$this->assertFalse($entity->hasPhone($phone));
		$entity->addPhone($phone);
		$this->assertTrue($entity->hasPhone($phone));
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
}