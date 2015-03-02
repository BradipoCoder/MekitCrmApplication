<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\CallBundle\Entity\Call;

class RelatedCallsTest extends MekitUnitEntityTest {

	public function testRelatedCalls() {
		$callOne = new Call();
		$callOne->setName("call-one");
		$callTwo = new Call();
		$callTwo->setName("call-two");

		$entity = new Account();
		$actual = $entity->getCalls();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$this->assertSame($entity, $entity->addCall($callOne));
		$this->assertTrue($entity->hasCall($callOne));
		$actual = $entity->getCalls();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($callOne), $actual->toArray());

		$this->assertSame($entity, $entity->addCall($callTwo));
		$this->assertTrue($entity->hasCall($callTwo));
		$actual = $entity->getCalls();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($callOne, $callTwo), $actual->toArray());

		$this->assertSame($entity, $entity->removeCall($callOne));
		$this->assertFalse($entity->hasCall($callOne));
		$actual = $entity->getCalls();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($callTwo), array_values($actual->toArray()));

		$this->assertSame($entity, $entity->removeCall($callTwo));
		$this->assertFalse($entity->hasCall($callTwo));
		$actual = $entity->getCalls();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$calls = new ArrayCollection(array($callOne, $callTwo));
		$this->assertSame($entity, $entity->setCalls($calls));
		$this->assertTrue($entity->hasCall($callOne));
		$this->assertTrue($entity->hasCall($callTwo));
		$actual = $entity->getCalls();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($calls, $actual);
	}
}