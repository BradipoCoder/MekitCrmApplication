<?php
namespace Mekit\Bundle\CrmBundle\Relationship;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;


class RelationshipManager
{
	/** @var  EntityManager */
	protected $entityManager;

	/** @var DoctrineHelper */
	protected $doctrineHelper;

	/**
	 * @param EntityManager $entityManager
	 * @param DoctrineHelper $doctrineHelper
	 */
	public function __construct(EntityManager $entityManager, DoctrineHelper $doctrineHelper) {
		$this->entityManager = $entityManager;
		$this->doctrineHelper = $doctrineHelper;
	}

	/**
	 * @param object $entity
	 * @param string $relatedEntityClass
	 * @param int    $relatedEntityId
	 */
	public function autoAssignRelatedEntity($entity, $relatedEntityClass, $relatedEntityId) {
		if ($this->entityHasAssociationTo($entity, $relatedEntityClass)) {
			$relatedEntity = $this->getEntity($relatedEntityClass, $relatedEntityId);
			if ($relatedEntity) {
				$this->autoAssign($entity, $relatedEntity);
			}
		}
	}

	/**
	 * Find and call the set/add method on entity with the relatedEntity
	 *
	 * @param object $entity
	 * @param object $relatedEntity
	 */
	private function autoAssign($entity, $relatedEntity) {
		$entityClass = ClassUtils::getClass($entity);
		$relatedEntityClass = ClassUtils::getClass($relatedEntity);
		$entityInfo = $this->getClassMetadataInfo($entityClass);
		$entityReflection = $entityInfo->getReflectionClass();
		$relatedEntityInfo = $this->getClassMetadataInfo($relatedEntityClass);
		$relatedEntityReflection = $relatedEntityInfo->getReflectionClass();
		$relatedEntityShortName = strtolower($relatedEntityReflection->getShortName());

		if($this->entityHasSingleAssociationTo($entity, $relatedEntityClass)) {
			$setMethodName = Inflector::camelize("set_" . $relatedEntityShortName);
			if ($entityReflection->hasMethod($setMethodName)) {
				call_user_func_array(
					[
						$entity,
						$setMethodName
					], [$relatedEntity]
				);
			}
		}

		if($this->entityHasCollectionAssociationTo($entity, $relatedEntityClass)) {
			$addMethodName = Inflector::camelize("add_" . $relatedEntityShortName);
			if ($entityReflection->hasMethod($addMethodName)) {
				call_user_func_array(
					[
						$entity,
						$addMethodName
					], [$relatedEntity]
				);
			}
		}
	}

	/**
	 * Checks if the entity has collection association(OneToMany OR ManyToMany) to a given entity
	 *
	 * @param object $entity
	 * @param string $targetEntityClass
	 * @return bool
	 */
	public function entityHasCollectionAssociationTo($entity, $targetEntityClass) {
		if (null === $entity || !is_object($entity)) {
			return false;
		}

		return ($this->getCollectionAssociationNameForTarget($entity, $targetEntityClass) !== false);
	}

	/**
	 * Checks if the entity has single association(ManyToOne OR OneToOne) to a given entity
	 *
	 * @param object $entity
	 * @param string $targetEntityClass
	 * @return bool
	 */
	public function entityHasSingleAssociationTo($entity, $targetEntityClass) {
		if (null === $entity || !is_object($entity)) {
			return false;
		}

		return ($this->getSingleAssociationNameForTarget($entity, $targetEntityClass) !== false);
	}

	/**
	 * Checks if the entity has any association to a given entity
	 *
	 * @param object $entity
	 * @param string $targetEntityClass
	 * @return bool
	 */
	public function entityHasAssociationTo($entity, $targetEntityClass) {
		if (null === $entity || !is_object($entity)) {
			return false;
		}

		return $this->entityHasSingleAssociationTo($entity, $targetEntityClass)
		       || $this->entityHasCollectionAssociationTo($entity, $targetEntityClass);
	}

	/**
	 * Checks if the entity has collection association(OneToMany OR ManyToMany) to a given entity and returns the name
	 * of the association
	 *
	 * @param object $entity
	 * @param string $targetEntityClass
	 * @return bool|string
	 */
	protected function getCollectionAssociationNameForTarget($entity, $targetEntityClass) {
		if (null === $entity || !is_object($entity)) {
			return false;
		}

		$className = ClassUtils::getClass($entity);
		$classInfo = $this->getClassMetadataInfo($className);
		$associations = $classInfo->getAssociationNames();
		foreach ($associations as $association) {
			if ($classInfo->getAssociationTargetClass($association) == $targetEntityClass
			    && $classInfo->isCollectionValuedAssociation($association)
			) {
				return $association;
			}
		}

		return false;
	}

	/**
	 * Checks if the entity has single association(ManyToOne OR OneToOne) to a given entity and returns the name
	 * of the association
	 *
	 * @param object $entity
	 * @param string $targetEntityClass
	 * @return bool|string
	 */
	protected function getSingleAssociationNameForTarget($entity, $targetEntityClass) {
		if (null === $entity || !is_object($entity)) {
			return false;
		}

		$className = ClassUtils::getClass($entity);
		$classInfo = $this->getClassMetadataInfo($className);
		$associations = $classInfo->getAssociationNames();
		foreach ($associations as $association) {
			if ($classInfo->getAssociationTargetClass($association) == $targetEntityClass
			    && $classInfo->isSingleValuedAssociation($association)
			) {
				return $association;
			}
		}

		return false;
	}

	/**
	 * @param $className
	 * @return ClassMetadata
	 */
	protected function getClassMetadataInfo($className) {
		return $this->entityManager->getMetadataFactory()->getMetadataFor($className);
	}

	/**
	 * Returns the entity object by its class name and id or false
	 *
	 * @param string $entityClass - FQCN
	 * @param int    $entityId
	 * @return null|object
	 */
	protected function getEntity($entityClass, $entityId) {
		try {
			$entity = $this->doctrineHelper->getEntity($entityClass, $entityId);
		} catch (\Exception $e) {
			$entity = null;
		}

		return $entity;
	}
}