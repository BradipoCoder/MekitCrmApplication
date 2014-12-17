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
		$qb = $this->getEntityManager()->getRepository($type)->createQueryBuilder('e')
			->orderBy("e.id");
		return($qb);
	}


//	public function getListItemQueryBuilder($ListGroupName) {
//		return $this->createQueryBuilder('li')
//			->innerJoin("li.listGroup", "lg")
//			->where('lg.name = :list_group_name')
//			->orderBy('li.label', 'ASC')
//			->setParameter('list_group_name', $ListGroupName);
//	}
}