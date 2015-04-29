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
	 * @param Array         $uid            - Array of user id
	 * @param \DateTime     $startDate
	 * @param \DateTime     $endDate
	 * @param string[]      $extraFields
	 *
	 * @return QueryBuilder
	 */
	public function getMeetingListByTimeIntervalQueryBuilder($uid, $startDate, $endDate, $extraFields = [])
	{
		$qb = $this->createQueryBuilder('m');
		$qb->select('m.id, m.name, e.description, e.startDate, e.endDate, e.createdAt, e.updatedAt')
			->innerJoin('m.event', 'e')
			->leftJoin("m.users", "assignees")
			->where('e.startDate >= :start AND e.endDate <= :end')
			->groupBy("m.id")
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
