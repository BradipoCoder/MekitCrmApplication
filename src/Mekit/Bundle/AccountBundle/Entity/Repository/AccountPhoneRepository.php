<?php
namespace Mekit\Bundle\AccountBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\AccountBundle\Entity\AccountPhone;

class AccountPhoneRepository  extends EntityRepository {
	/**
	 * @param Account $account
	 * @return QueryBuilder
	 */
	public function getAccountPhoneQueryBuilder(Account $account) {
		return $this->createQueryBuilder('p')
			->where('p.owner = :account')
			->orderBy('p.primary', 'DESC')
			->setParameter('account', $account);
	}

	/**
	 * @param Account $account
	 * @return AccountPhone[]
	 */
	public function getContactPhones(Account $account) {
		$query = $this->getAccountPhoneQueryBuilder($account)->getQuery();
		return $query->execute();
	}
}
