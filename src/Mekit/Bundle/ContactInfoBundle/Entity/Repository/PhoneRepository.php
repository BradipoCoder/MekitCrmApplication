<?php
namespace Mekit\Bundle\ContactInfoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ContactInfoBundle\Entity\Phone;

/**
 * Class PhoneRepository
 */
class PhoneRepository extends EntityRepository {
	/**
	 * @param Account $account
	 * @return QueryBuilder
	 */
	public function getAccountPhoneQueryBuilder(Account $account) {
		return $this->createQueryBuilder('p')
			->where('p.owner_account = :account')
			->orderBy('p.primary', 'DESC')
			->setParameter('account', $account);
	}

	/**
	 * @param Contact $contact
	 * @return QueryBuilder
	 */
	public function getContactPhoneQueryBuilder(Contact $contact) {
		return $this->createQueryBuilder('p')
			->where('p.owner_contact = :contact')
			->orderBy('p.primary', 'DESC')
			->setParameter('contact', $contact);
	}

	/**
	 * @param Account $account
	 * @return Phone[]
	 */
	public function getAccountPhones(Account $account) {
		$query = $this->getAccountPhoneQueryBuilder($account)->getQuery();
		return $query->execute();
	}

	/**
	 * @param Contact $contact
	 * @return Phone[]
	 */
	public function getContactPhones(Contact $contact) {
		$query = $this->getContactPhoneQueryBuilder($contact)->getQuery();
		return $query->execute();
	}

}
