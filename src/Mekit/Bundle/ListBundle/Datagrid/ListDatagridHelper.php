<?php
namespace Mekit\Bundle\ListBundle\Datagrid;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

class ListDatagridHelper {


	/**
	 * ACCOUNT_TYPE
	 * @return callable
	 */
	public function getListItemQueryBuilder_ACCOUNT_TYPE() {
		return function (EntityRepository $er) {
			return $this->getListItemQueryBuilderForGroup($er, "ACCOUNT_TYPE");
		};
	}

	/**
	 * ACCOUNT_INDUSTRY
	 * @return callable
	 */
	public function getListItemQueryBuilder_ACCOUNT_INDUSTRY() {
		return function (EntityRepository $er) {
			return $this->getListItemQueryBuilderForGroup($er, "ACCOUNT_INDUSTRY");
		};
	}

	/**
	 * ACCOUNT_STATE
	 * @return callable
	 */
	public function getListItemQueryBuilder_ACCOUNT_STATE() {
		return function (EntityRepository $er) {
			return $this->getListItemQueryBuilderForGroup($er, "ACCOUNT_STATE");
		};
	}

	/**
	 * @param EntityRepository $er
	 * @param  String $listGroupName
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	private function getListItemQueryBuilderForGroup(EntityRepository $er, $listGroupName) {
		return $er->createQueryBuilder('li')
			->innerJoin("li.listGroup", "lg")
			->where('lg.name = :list_group_name')
			->orderBy('li.label', 'ASC')
			->setParameter('list_group_name', $listGroupName);
	}
}