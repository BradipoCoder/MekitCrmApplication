<?php
namespace Mekit\Bundle\RelationshipBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;

class ReferenceableElementRepository extends EntityRepository {

	/**
	 * @param string $type
	 * @return QueryBuilder
	 */
	public function getReferencedElementsQueryBuilderByType($type) {
		$qb = $this->getEntityManager()->createQueryBuilder()
			->select('el')
			->from($type, 'el')
			//->innerJoin('el.referenceableElement', 're')
			->orderBy("el.id");
		return($qb);
	}

}