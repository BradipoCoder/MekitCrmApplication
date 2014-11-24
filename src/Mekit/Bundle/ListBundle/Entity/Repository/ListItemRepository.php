<?php
namespace Mekit\Bundle\ListBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\ListItem;


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

	/**
	 * Returnd a specific ListItem given the group name and the item label
	 * @param string $groupName
	 * @param string $itemLabel
	 * @return null|ListItem
	 */
	public function getItemFromGroupByLabel($groupName, $itemLabel) {
		/** @var ListGroup $listGroup */
		$listGroup = $this->getEntityManager()->getRepository('MekitListBundle:ListGroup')->findOneBy(["name"=>$groupName]);
		$listItem = $this->findOneBy(["listGroup"=>$listGroup, "label"=>$itemLabel]);
		return($listItem);
	}
}