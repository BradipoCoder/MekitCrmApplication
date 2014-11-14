<?php
namespace Mekit\Bundle\ListBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;


class ListItemRepository extends EntityRepository {

	/**
	 * @param string $ListGroupName
	 * @return QueryBuilder
	 */
	public function getListItemQueryBuilder($ListGroupName) {
		return $this->createQueryBuilder('li')
			->innerJoin("li.listGroup", "lg")
			->where('lg.name = :list_group_name')
			->orderBy('li.label', 'ASC')
			->setParameter('list_group_name', $ListGroupName);
	}

	/**
	 * @param $ListGroupName
	 * @return ListItem[]
	 */
	public function getListItems($ListGroupName) {
		$query = $this->getListItemQueryBuilder($ListGroupName)->getQuery();
		return $query->execute();
	}
}