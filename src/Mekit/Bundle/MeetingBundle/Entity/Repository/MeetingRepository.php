<?php
namespace Mekit\Bundle\MeetingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class MeetingRepository extends EntityRepository
{
	/**
	 * Returns a query builder which can be used to get a list of meetings filtered by start and end dates
	 *
	 * @param int       $userId
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @param string[]  $extraFields
	 *
	 * @return QueryBuilder
	 */
	public function getMeetingListByTimeIntervalQueryBuilder($userId, $startDate, $endDate, $extraFields = [])
	{
		$qb = $this->createQueryBuilder('m')
			->select('m.id, m.name, e.description, e.startDate, e.endDate, e.createdAt, e.updatedAt')
			->innerJoin('m.event', 'e')
			->leftJoin("m.users","assignees")
			->where('assignees.id = :assignedTo AND e.startDate >= :start AND e.endDate <= :end')
			->groupBy("m.id")
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
