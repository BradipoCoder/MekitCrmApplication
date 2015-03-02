<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\TaskBundle\Entity\Task;

class RelatedTasksTest extends MekitUnitEntityTest {

	public function testRelatedTasks() {
		$taskOne = new Task();
		$taskOne->setName("task-one");
		$taskTwo = new Task();
		$taskTwo->setName("task-two");

		$entity = new Account();
		$actual = $entity->getTasks();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$this->assertSame($entity, $entity->addTask($taskOne));
		$this->assertTrue($entity->hasTask($taskOne));
		$actual = $entity->getTasks();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($taskOne), $actual->toArray());

		$this->assertSame($entity, $entity->addTask($taskTwo));
		$this->assertTrue($entity->hasTask($taskTwo));
		$actual = $entity->getTasks();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($taskOne, $taskTwo), $actual->toArray());

		$this->assertSame($entity, $entity->removeTask($taskOne));
		$this->assertFalse($entity->hasTask($taskOne));
		$actual = $entity->getTasks();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($taskTwo), array_values($actual->toArray()));

		$this->assertSame($entity, $entity->removeTask($taskTwo));
		$this->assertFalse($entity->hasTask($taskTwo));
		$actual = $entity->getTasks();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$tasks = new ArrayCollection(array($taskOne, $taskTwo));
		$this->assertSame($entity, $entity->setTasks($tasks));
		$this->assertTrue($entity->hasTask($taskOne));
		$this->assertTrue($entity->hasTask($taskTwo));
		$actual = $entity->getTasks();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($tasks, $actual);
	}
}