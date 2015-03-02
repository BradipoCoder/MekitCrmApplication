<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ProjectBundle\Entity\Project;

class RelatedProjectsTest extends MekitUnitEntityTest {

	public function testRelatedProjects() {
		$projectOne = new Project();
		$projectOne->setName("project-one");
		$projectTwo = new Project();
		$projectTwo->setName("project-two");
		$projects = new ArrayCollection([$projectOne, $projectTwo]);

		$entity = new Account();
		$actual = $entity->getProjects();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$this->assertSame($entity, $entity->setProjects($projects));
		$actual = $entity->getProjects();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($projects->count(), $actual->count());
		$this->assertEquals($projects, $actual);
	}
}