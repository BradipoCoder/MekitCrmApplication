<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Oro\Bundle\UserBundle\Entity\User;


class RelatedUsersTest extends MekitUnitEntityTest {

	public function testRelatedUsers() {
		$userOne = new User();
		$userOne->setFirstName("user-one");
		$userTwo = new User();
		$userTwo->setFirstName("user-two");

		$entity = new Account();
		$actual = $entity->getUsers();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$this->assertSame($entity, $entity->addUser($userOne));
		$this->assertTrue($entity->hasUser($userOne));
		$actual = $entity->getUsers();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($userOne), $actual->toArray());

		$this->assertSame($entity, $entity->addUser($userTwo));
		$this->assertTrue($entity->hasUser($userTwo));
		$actual = $entity->getUsers();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($userOne, $userTwo), $actual->toArray());

		$this->assertSame($entity, $entity->removeUser($userOne));
		$this->assertFalse($entity->hasUser($userOne));
		$actual = $entity->getUsers();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($userTwo), array_values($actual->toArray()));

		$this->assertSame($entity, $entity->removeUser($userTwo));
		$this->assertFalse($entity->hasUser($userTwo));
		$actual = $entity->getUsers();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$users = new ArrayCollection(array($userOne, $userTwo));
		$this->assertSame($entity, $entity->setUsers($users));
		$this->assertTrue($entity->hasUser($userOne));
		$this->assertTrue($entity->hasUser($userTwo));
		$actual = $entity->getUsers();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($users, $actual);
	}
}