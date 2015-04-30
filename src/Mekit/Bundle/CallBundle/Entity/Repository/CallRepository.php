<?php
namespace Mekit\Bundle\CallBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class CallRepository extends EntityRepository
{
	/**
	 * Returns a query builder which can be used to get a list of calls filtered by start and end dates
	 *
	 * @param Array         $uid            - Array of user ids
	 * @param \DateTime     $startDate
	 * @param \DateTime     $endDate
	 * @param string[]      $extraFields
	 *
	 * @return QueryBuilder
	 */
	public function getCallListByTimeIntervalQueryBuilder($uid, $startDate, $endDate, $extraFields = [])
	{
		$qb = $this->createQueryBuilder('c');
		$qb->select('c.id, c.name, e.description, e.startDate, e.endDate, e.createdAt, e.updatedAt')
			->innerJoin('c.event', 'e')
			->leftJoin("c.users", "assignees")
			->where('e.startDate >= :start AND e.endDate <= :end')
			->groupBy("c.id")
			->setParameter('start', $startDate)
			->setParameter('end', $endDate);
		if(count($uid)) {
			$qb->andWhere($qb->expr()->in('assignees.id', $uid));
		} else {
			$qb->andWhere('assignees.id = 0');
		}
		if ($extraFields) {
			foreach ($extraFields as $field) {
				$qb->addSelect($field);
			}
		}
		return $qb;
	}
}