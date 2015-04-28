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
	 * @param int       $userId
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @param string[]  $extraFields
	 *
	 * @return QueryBuilder
	 */
	public function getTaskListByTimeIntervalQueryBuilder($userId, $startDate, $endDate, $extraFields = [])
	{
		$qb = $this->createQueryBuilder('t')
			->select('t.id, t.name, e.description, e.startDate, e.endDate, e.createdAt, e.updatedAt')
			->innerJoin('t.event', 'e')
			->leftJoin("t.users","assignees")
			->where('assignees.id = :assignedTo AND e.startDate >= :start AND e.endDate <= :end')
			->groupBy("t.id")
			->setParameter('assignedTo', $userId)
			->setParameter('start', $startDate)
			->setParameter('end', $endDate);
		if ($extraFields) {
			foreach ($extraFields as $field) {
				$qb->addSelect($field);
			}
		}
		return $qb;
	}
}