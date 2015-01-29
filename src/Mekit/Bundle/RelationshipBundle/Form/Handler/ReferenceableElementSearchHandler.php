<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Oro\Bundle\FormBundle\Autocomplete\SearchHandler;

//use Oro\Bundle\EntityBundle\Form\Handler\EntitySelectHandler; // using this as model

class ReferenceableElementSearchHandler extends SearchHandler {
	/** @var ManagerRegistry */
	protected $registry;

	public function __construct() {
		// give some values in order to prevent warnings
		parent::__construct(false, []);
	}

	/**
	 * @param string $entityName Entity name to prepare search handler for
	 * @param array  $targetFields Entity fields to search by and include to search results
	 */
	public function initForEntity($entityName, $targetFields) {
		$this->entityName = str_replace('_', '\\', $entityName);
		$this->initDoctrinePropertiesByEntityManager($this->registry->getManagerForClass($this->entityName));
		$this->properties = array_unique($targetFields);
	}

	/**
	 * {@inheritdoc}
	 * query format: '[searched string];[entity name];[list of fields separated by commas]'  --> 'xxx;Mekit_Bundle_AccountBundle_Entity_Account;name,vatid'
	 */
	public function search($query, $page, $perPage, $searchById = false) {
		list($query, $targetEntity, $targetFields) = explode(';', $query);
		$targetFields = explode(',', $targetFields);
		$this->initForEntity($targetEntity, $targetFields);
		return parent::search($query, $page, $perPage, $searchById);
	}

	/**
	 * Returns results for the searched string.
	 * If the entity repository has specific method(getQueryBuilderForReferenceableElementAutocomplete) it will be used
	 * otherwise a generic query will be used based on properties defined in 'entity_fields' configs option
	 * @param string $search
	 * @param int    $firstResult
	 * @param int    $maxResults
	 * @return array
	 */
	protected function searchEntities($search, $firstResult, $maxResults) {
		$entitySpecificSearchMethod = 'getQueryBuilderForReferenceableElementAutocomplete';
		if(method_exists($this->entityRepository, $entitySpecificSearchMethod)) {
			/** @var QueryBuilder $qb */
			$qb = call_user_func_array([$this->entityRepository, $entitySpecificSearchMethod],[$search]);
		}
		if (!isset($qb) || !$qb instanceof QueryBuilder) {
			$qb = $this->entityRepository->createQueryBuilder('entity');
			$qb->innerJoin('entity.referenceableElement', 're');
			$orX = $qb->expr()->orX();
			foreach ($this->properties as $prop) {
				$orX->add($qb->expr()->like('entity.' . $prop, $qb->expr()->literal('%' . $search . '%')));
			}
			$qb->where($orX);
		}
		$qb->setMaxResults($maxResults);
		$qb->setFirstResult($firstResult);
		return $qb->getQuery()->getResult();//getArrayResult();
	}

	/**
	 * Get search results data by id
	 *
	 * @param int $query - comma separated list of ReferenceableElement IDs
	 * @return array
	 */
	protected function findById($query) {
		$ids = explode(',', $query);
		$items = [];
		if(count($ids)) {
			$qb = $this->entityRepository->createQueryBuilder('entity');
			$qb->innerJoin('entity.referenceableElement', 're');
			$qb->where('re.id IN (:ids)')->setParameter('ids', $ids);
			$items = $qb->getQuery()->getResult();
		}
		return $items;
	}

	/**
	 * {@inheritdoc}
	 * $item should be entity of type as defined by $this->entityName
	 */
	public function convertItem($item) {
		$result = [];

		//forcing to use id of referenceableElement
		if($this->getPropertyValue("referenceableElementId", $item)) {
			$result['id'] = $this->getPropertyValue("referenceableElementId", $item);
		} else {
			$referenceableElement = $this->getPropertyValue('referenceableElement', $item);
			$result['id'] = $this->getPropertyValue('id', $referenceableElement);
		}

		//try to add Item To String (i2s) property by calling entity's magic __toString method
		//this is the default property used by the result and selection templates
		$method = '__toString';
		if (method_exists($item, $method)) {
			$result['i2s'] = $item->$method();
		}

		//add the other specific properties defined by 'entity_fields'
		foreach ($this->properties as $property) {
			if( ($properyValue = $this->getPropertyValue($property, $item)) ) {
				$result[$property] = $properyValue;
			}
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function initDoctrinePropertiesByManagerRegistry(ManagerRegistry $managerRegistry) {
		$this->registry = $managerRegistry;
	}

	/**
	 * @throws \RuntimeException
	 */
	protected function checkAllDependenciesInjected() {
		if (!$this->properties || !$this->entityRepository || !$this->idFieldName) {
			throw new \RuntimeException('ReferenceableElementSearchHandler handler is not fully configured');
		}
	}
}