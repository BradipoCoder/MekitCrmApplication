<?php
namespace Mekit\Bundle\TaskBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class TaskRepository extends EntityRepository
{
	/**
	 * Returns a query builder which can be used to get a list of tasks filtered by start and end dates
	 *
	 * @param Array         $uid            - Array of user ids
	 * @param \DateTime     $startDate
	 * @param \DateTime     $endDate
	 * @param string[]      $extraFields
	 *
	 * @return QueryBuilder
	 */
	public function getTaskListByTimeIntervalQueryBuilder($uid, $startDate, $endDate, $extraFields = [])
	{
		$qb = $this->createQueryBuilder('t');
		$qb->select('t.id, t.name, e.description, e.startDate, e.endDate, e.createdAt, e.updatedAt')
			->innerJoin('t.event', 'e')
			->leftJoin("t.users", "assignees")
			->where('e.startDate >= :start AND e.endDate <= :end')
			->groupBy("t.id")
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