<?php
namespace Mekit\Bundle\TestBundle\Helpers;

/**
 * Some common helper methods
 * Class MekitUnitEntityTest
 */
class MekitUnitEntityTest extends MekitUnitTest {
	/**
	 * Test if entity has __toString method
	 * @param string $entityName
	 */
	protected function hasToStringMethod($entityName) {
		$class = new \ReflectionClass($entityName);
		$this->assertTrue($class->hasMethod('__toString'), "Check method: __toString");
	}

	/**
	 * Test getter/setter methods on entity
	 * @param string $entityName
	 * @param string $property
	 * @param mixed $value
	 * @param mixed $expected
	 */
	protected function checkGetterSetterMethods($entityName, $property, $value, $expected=null) {
		$expected = ($expected ? $expected : $value);
		$class = new \ReflectionClass($entityName);
		$entity = new $entityName();
		$getter = 'get' . ucfirst($property);
		$setter = 'set' . ucfirst($property);
		$this->assertTrue($class->hasMethod($getter), "Check method: " . $getter);
		$this->assertTrue($class->hasMethod($setter), "Check method: " . $setter);
		$actual = call_user_func_array(array($entity, $setter), array($value));
		$this->assertSame($entity, $actual);
		$this->assertEquals($expected, call_user_func_array(array($entity, $getter), array()));
	}
}