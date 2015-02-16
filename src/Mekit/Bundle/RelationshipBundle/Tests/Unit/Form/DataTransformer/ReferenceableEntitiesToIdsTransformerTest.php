<?php
namespace Mekit\Bundle\RelationshipBundle\Tests\Unit\Form\DataTransformer;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitTest;
use Mekit\Bundle\RelationshipBundle\Form\DataTransformer\ReferenceableEntitiesToIdsTransformer;

class ReferenceableEntitiesToIdsTransformerTest extends MekitUnitTest {
	/**
	 * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
	 */
	private $entityManager;

	/**
	 * @dataProvider transformDataProvider
	 *
	 * @param string $property
	 * @param mixed  $value
	 * @param mixed  $expectedValue
	 */
	public function testTransform($property, $value, $expectedValue) {
		$transformer = new ReferenceableEntitiesToIdsTransformer($this->getMockEntityManager(), 'TestClass', $property, null);
		$this->assertEquals($expectedValue, $transformer->transform($value));
	}

	/**
	 * @return array
	 */
	public function transformDataProvider() {
		return array(
			'default' => array(
				'id',
				$this->createMockEntityList('id', array(1, 2, 3, 4)),
				array(1, 2, 3, 4)
			),
			'empty' => array(
				'id',
				array(),
				array()
			),
		);
	}

	/**
	 * @expectedException \Symfony\Component\Form\Exception\UnexpectedTypeException
	 * @expectedExceptionMessage Expected argument of type "array|\Traversable", "string" given
	 */
	public function testTransformFailsWhenValueIsNotAnArray() {
		$transformer = new ReferenceableEntitiesToIdsTransformer($this->getMockEntityManager(), 'TestClass', 'id', null);
		$transformer->transform('invalid value');
	}



	/**
	 * Create list of mocked entities by id property name and values
	 *
	 * @param string $property
	 * @param array  $values
	 * @return \PHPUnit_Framework_MockObject_MockObject[]
	 */
	private function createMockEntityList($property, array $values) {
		$result = array();
		foreach ($values as $value) {
			$result[] = $this->createMockEntity($property, $value);
		}

		return $result;
	}

	/**
	 * Create mock entity by id property name and value
	 *
	 * @param string $property
	 * @param mixed  $value
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	private function createMockEntity($property, $value) {
		$getter = 'get' . ucfirst($property);
		$result = $this->getMock('MockEntity', array($getter));
		$result->expects($this->any())->method($getter)->will($this->returnValue($value));

		return $result;
	}

	/**
	 * @return EntityManager|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getMockEntityManager() {
		if (!$this->entityManager) {
			$this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
				->disableOriginalConstructor()
				->setMethods(array('getClassMetadata', 'getRepository'))
				->getMockForAbstractClass();
		}

		return $this->entityManager;
	}


}