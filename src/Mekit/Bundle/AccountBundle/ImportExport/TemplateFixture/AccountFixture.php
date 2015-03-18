<?php
namespace Mekit\Bundle\AccountBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactInfoBundle\Entity\Email;
use Mekit\Bundle\ContactInfoBundle\Entity\Phone;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;

class AccountFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Mekit\Bundle\AccountBundle\Entity\Account';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Mekit');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Account();
    }

    /**
     * @param string  $key
     * @param Account $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');

        switch ($key) {
            case 'Mekit':
	            $primaryAddress = $this->createAddress(1);
                $entity
                    ->setId(1)
                    ->setName($key)
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setOrganization($organizationRepo->getEntity('default'))
                    ->addEmail($this->createEmail("email1@test.com"), true)
	                ->addEmail($this->createEmail("email2@test.com"))
	                ->addPhone($this->createPhone("011 123.45.67", true))
	                ->addPhone($this->createPhone("011 123.45.68"))
                    ->addAddress($primaryAddress)->setPrimaryAddress($primaryAddress);
                return;
        }

        parent::fillEntityData($key, $entity);
    }

	/**
	 * @param string $email
	 * @param bool   $primary
	 *
	 * @return Email
	 */
	protected function createEmail($email, $primary = false)
	{
		$entity = new Email($email);
		if ($primary) {
			$entity->setPrimary(true);
		}

		return $entity;
	}

	/**
	 * @param string $phone
	 * @param bool   $primary
	 *
	 * @return Phone
	 */
	protected function createPhone($phone, $primary = false)
	{
		$entity = new Phone($phone);
		if ($primary) {
			$entity->setPrimary(true);
		}

		return $entity;
	}

	/**
	 * @param int    $number
	 *
	 * @return Address
	 *
	 * @throws \LogicException
	 */
	protected function createAddress($number)
	{
		$countryRepo = $this->templateManager
			->getEntityRepository('Oro\Bundle\AddressBundle\Entity\Country');
		$regionRepo  = $this->templateManager
			->getEntityRepository('Oro\Bundle\AddressBundle\Entity\Region');

		$entity = new Address();



		switch ($number) {
			case 1:
				$entity
					->setCity('Torino')
					->setStreet('C.so Vittorio Emanuele, 127')
					->setPostalCode('10128')
					->setRegion($regionRepo->getEntity('NY'))
					->setCountry($countryRepo->getEntity('US'));
				break;
			default:
				throw new \LogicException(
					sprintf(
						'Undefined contact address. Number: %d.',
						$number
					)
				);
		}

		return $entity;
	}

}
