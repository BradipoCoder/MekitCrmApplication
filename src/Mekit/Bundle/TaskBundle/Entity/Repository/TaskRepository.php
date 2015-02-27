<?php
namespace Mekit\Bundle\TaskBundle\Entity\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class TaskRepository extends EntityRepository {
	/**
	 * Finds an entity by its primary key / identifier.
	 *
	 * @param mixed    $id          The identifier.
	 * @param int      $lockMode    The lock mode.
	 * @param int|null $lockVersion The lock version.
	 *
	 * @return object|null The entity instance or NULL if the entity can not be found.
	 */
	/*
	public function find($id, $lockMode = LockMode::NONE, $lockVersion = null) {
		$qb = $this->createQueryBuilder('t')
			->select('t', 'e', 'acc', 'cnt', 'u', 'c', 'm')
			->innerJoin('t.event', 'e')
			->leftJoin('t.accounts', 'acc')
			->leftJoin('t.contacts', 'cnt')
			->leftJoin('t.users', 'u')
			->leftJoin('t.calls', 'c')
			->leftJoin('t.meetings', 'm')
			->where("t.id = :id")
			->setParameter("id", $id);
		$query = $qb->getQuery();
		return $query->getSingleResult();
	}*/
}