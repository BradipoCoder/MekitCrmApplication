<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Oro\Bundle\FormBundle\Autocomplete\SearchHandler;

//use Oro\Bundle\EntityBundle\Form\Handler\EntitySelectHandler; // using this as model

class ReferenceableElementSearchHandler extends SearchHandler {
	const ENTITY_NAME = 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement';

	/** @var array */
	protected $defaultPropertySet = ['text'];

	/** @var string */
	protected $currentField;

	/** @var ManagerRegistry */
	protected $registry;

	public function __construct() {
		// give some values in order to prevent warnings
		parent::__construct(self::ENTITY_NAME, []);
	}

	/**
	 * @param string $entityName Entity name to prepare search handler for
	 * @param string $targetField Entity field to search by and include to search results
	 */
	public function initForEntity($entityName, $targetField) {
		$this->entityName = str_replace('_', '\\', $entityName);
		$this->initDoctrinePropertiesByEntityManager($this->registry->getManagerForClass($this->entityName));

		$this->properties = array_unique(array_merge($this->defaultPropertySet, [$targetField]));
		$this->currentField = $targetField;
	}

	/**
	 * {@inheritdoc}
	 */
	public function search($query, $page, $perPage, $searchById = false) {
		//echo "\n<br />(search)Q: " . $query;

		//list($query, $targetEntity, $targetField) = explode(',', $query);
		$this->initForEntity(self::ENTITY_NAME, 'name');

		return parent::search($query, $page, $perPage, $searchById);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function searchEntities($search, $firstResult, $maxResults) {
		//echo "\n<br />(searchEntities)Q: " . $search;

		$queryBuilder = $this->entityRepository->createQueryBuilder('re');
		$queryBuilder->select("re.id",'a.name');
		$queryBuilder->innerJoin('re.account', 'a');

		$queryBuilder->where(
			$queryBuilder->expr()->like(
				'a.' . $this->currentField,
				$queryBuilder->expr()->literal($search . '%')
			)
		);
		$queryBuilder->setMaxResults($maxResults);
		$queryBuilder->setFirstResult($firstResult);

		return $queryBuilder->getQuery()->getArrayResult();
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
		if (!$this->properties || !$this->currentField || !$this->entityRepository || !$this->idFieldName) {
			throw new \RuntimeException('ReferenceableElementSearchHandler handler is not fully configured');
		}
	}
}