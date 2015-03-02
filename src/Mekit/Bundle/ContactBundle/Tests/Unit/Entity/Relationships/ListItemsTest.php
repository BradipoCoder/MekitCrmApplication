<?php
namespace Mekit\Bundle\ContactBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Mekit\Bundle\ListBundle\Entity\ListItem;


class ListItemsTest extends MekitUnitEntityTest{
	/** @var string */
	protected $entityName = 'Mekit\Bundle\ContactBundle\Entity\Contact';

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
		$listItem = new ListItem();
		return array(
			array('jobTitle', $listItem),
		);
	}

}