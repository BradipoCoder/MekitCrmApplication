<?php
namespace Mekit\Bundle\ProjectBundle\Entity\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class ProjectRepository extends EntityRepository {
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
		$qb = $this->createQueryBuilder('p')
			->select('p', 'own', 'org', 'a', 'u', 't', 'c', 'm')
			->innerJoin('p.owner', 'own')
			->innerJoin('p.organization', 'org')
			->innerJoin('p.account', 'a')
			->leftJoin('p.users', 'u')
			->leftJoin('p.tasks', 't')
			->leftJoin('p.calls', 'c')
			->leftJoin('p.meetings', 'm')
			->where("p.id = :id")
			->setParameter("id", $id);
		$query = $qb->getQuery();
		return $query->getSingleResult();
	}*/
}