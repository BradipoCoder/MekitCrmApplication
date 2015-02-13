<?php
namespace Mekit\Bundle\TestBundle\Helpers;

/**
 * @todo: we need interface for this class
 * Class MekitEntityTest
 */
class MekitEntityTest extends MekitTest {
	/**
	 * @var string
	 */
	protected $entityName;

	/**
	 * Tests if entity has working __toString method
	 */
	public function testToStringMethod() {
		$class = new \ReflectionClass($this->entityName);
		$this->assertTrue($class->hasMethod('__toString'), "Check method: __toString");
	}


	/**
	 *
	 * Important! $entityName must be set for this to work
	 *
	 * @dataProvider entityPropertyProvider
	 * @param string $property
	 * @param mixed $value
	 * @param mixed $expected
	 */

	public function testSettersAndGetters($property, $value, $expected=null) {
		$entity = new $this->entityName();
		$expected = ($expected ? $expected : $value);
		$getter = 'get' . ucfirst($property);
		$setter = 'set' . ucfirst($property);
		call_user_func_array(array($entity, $setter), array($value));
		$this->assertEquals($expected, call_user_func_array(array($entity, $getter), array()));
	}
}