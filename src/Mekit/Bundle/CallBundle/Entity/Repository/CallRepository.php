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
	 * @param int       $userId
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @param string[]  $extraFields
	 *
	 * @return QueryBuilder
	 */
	public function getCallListByTimeIntervalQueryBuilder($userId, $startDate, $endDate, $extraFields = [])
	{
		$qb = $this->createQueryBuilder('c')
			->select('c.id, c.name, e.description, e.startDate, e.endDate, e.createdAt, e.updatedAt')
			->innerJoin('c.event', 'e')
			->leftJoin("c.users","assignees")
			->where('assignees.id = :assignedTo AND e.startDate >= :start AND e.endDate <= :end')
			->groupBy("c.id")
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