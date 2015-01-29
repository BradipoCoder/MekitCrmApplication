<?php
namespace Mekit\Bundle\MeetingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class MeetingRepository extends EntityRepository {

	/**
	 * Specific search qb for ReferenceableElement Autocomplete search used by ReferenceableElementSearchHandler
	 * Note: if you set any select on qb make sure to include "referenceableElementId" and "i2s"(the string that will be shown in dropdown)
	 * @param String $search - the search string typed by user
	 * @return QueryBuilder
	 */
	public function getQueryBuilderForReferenceableElementAutocomplete($search) {
		$qb = $this->createQueryBuilder("m")
			->select("re.id as referenceableElementId", "ev.name as i2s")
			->innerJoin('m.referenceableElement', 're')
			->innerJoin('m.event','ev');
		if(!empty($search)) {
			$orX = $qb->expr()->orX();
			$orX->add($qb->expr()->like('ev.name', $qb->expr()->literal('%' . $search . '%')));
			$orX->add($qb->expr()->like('m.description', $qb->expr()->literal('%' . $search . '%')));
			$qb->where($orX);
		}
		return $qb;
	}
}