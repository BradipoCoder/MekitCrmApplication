<?php
namespace Mekit\Bundle\TaskBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class TaskRepository extends EntityRepository {

	/**
	 * Specific search qb for ReferenceableElement Autocomplete search used by ReferenceableElementSearchHandler
	 * Note: if you set any select on qb make sure to include "referenceableElementId" and "i2s"(the string that will be shown in dropdown)
	 * @param String $search - the search string typed by user
	 * @return QueryBuilder
	 */
	public function getQueryBuilderForReferenceableElementAutocomplete($search) {
		$qb = $this->createQueryBuilder("t")
			->select("re.id as referenceableElementId", "ev.name as i2s")
			->innerJoin('t.referenceableElement', 're')
			->innerJoin('t.event','ev');
		if(!empty($search)) {
			$orX = $qb->expr()->orX();
			$orX->add($qb->expr()->like('ev.name', $qb->expr()->literal('%' . $search . '%')));
			$orX->add($qb->expr()->like('t.description', $qb->expr()->literal('%' . $search . '%')));
			$qb->where($orX);
		}
		return $qb;
	}

}