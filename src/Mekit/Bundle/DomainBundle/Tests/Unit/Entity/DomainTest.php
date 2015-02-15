<?php

namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity;

use Mekit\Bundle\DomainBundle\Entity\Domain;
use Mekit\Bundle\TestBundle\Tests\Helpers\MekitEntityTests;


class DomainTest extends MekitEntityTests {

	protected $entityName = 'Mekit\Bundle\DomainBundle\Entity\Domain';

	/** @var Domain */
	protected $domain;

	protected function setUp()
	{
		$this->domain = new Domain();
	}

	public function testName()
	{
		$name = 'testName';
		$this->assertNull($this->domain->getName());
		$this->domain->setName($name);
		$this->assertEquals($name, $this->domain->getName());
		$this->assertEquals($name, (string)$this->domain);
	}

	public function testId()
	{
		$this->assertNull($this->domain->getId());
	}



	/**
	 * Data provider for simple get/set tests executed in MekitEntityTests::testSettersAndGetters(prop, value, expected)
	 * Properties must follow getter/setter naming convention
	 *
	 * @return array
	 */
	public function propertyTestsProvider() {
		$now = new \DateTime('now');

		return array(
			array('id', '123'),
			array('name', 'MEKIT'),
			array('description', 'bla bla test'),
			array('provider', 'mekit.it'),
			array('expiration', $now),
			array('createdAt', $now),
			array('updatedAt', $now)
		);
	}
}