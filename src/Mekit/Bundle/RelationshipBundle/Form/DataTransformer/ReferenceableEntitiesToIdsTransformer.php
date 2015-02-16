<?php

namespace Mekit\Bundle\RelationshipBundle\Form\DataTransformer;

use Oro\Bundle\FormBundle\Form\DataTransformer\EntitiesToIdsTransformer;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\MappingException;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ReferenceableEntitiesToIdsTransformer extends EntitiesToIdsTransformer {
	/** @var  PropertyAccessor */
	protected $propertyAccessor;

	/**
	 * @todo: this is not good - it should receive an array of ReferenceableElements as input and return their ids
	 * @param array|\Traversable $value - array of referenceable entities
	 * @return array - array of ids of referenceableElements
	 */
	public function transform($value) {
		if (null === $value || array() === $value) {
			return array();
		}

		if (!is_array($value) && !$value instanceof \Traversable) {
			throw new UnexpectedTypeException($value, 'array|\Traversable');
		}

		$result = array();
		foreach ($value as $entity) {
			//$id = $this->propertyAccessor->getValue($entity, 'referenceableElement.id');
			$id = $this->propertyAccessor->getValue($entity, 'id');
			$result[] = $id;
		}
		return $result;
	}

	/**
	 * @param array|\Traversable $value - array of ids of referenceableElements
	 * @return array - array of referenceable elements
	 */
	public function reverseTransform($value) {
		if (!is_array($value) && !$value instanceof \Traversable) {
			throw new UnexpectedTypeException($value, 'array|\Traversable');
		}

		//Convert \Traversable to Array for loadEntitiesByIds
		$ids = [];
		if(count($value)) {
			foreach($value as $v) {
				$ids[] = $v;
			}
		}

		if (!count($ids)) {
			return array();
		}

		$entities = $this->loadEntitiesByIds($ids);

		if (count($entities) !== count($ids)) {
			throw new TransformationFailedException('Could not find all entities for the given IDs');
		}
		return $entities;
	}

	/**
	 * Load entities by array of ids
	 *
	 * @param array $ids
	 * @return array
	 * @throws UnexpectedTypeException if query builder callback returns invalid type
	 */
	protected function loadEntitiesByIds(array $ids) {
		$repository = $this->em->getRepository('Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement');//$this->className
		if ($this->queryBuilderCallback) {
			/** @var $qb QueryBuilder */
			$qb = call_user_func($this->queryBuilderCallback, $repository, $ids);
			if (!$qb instanceof QueryBuilder) {
				throw new UnexpectedTypeException($qb, 'Doctrine\ORM\QueryBuilder');
			}
		} else {
			$qb = $repository->createQueryBuilder('re');
			$qb->where('re.id IN (:ids)')->setParameter('ids', $ids);
		}
		return $qb->getQuery()->execute();
	}
}