<?php
namespace Mekit\Bundle\ContactBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;
use Mekit\Bundle\ContactInfoBundle\Entity\Email;
use Mekit\Bundle\ContactInfoBundle\Entity\Phone;


class ContactFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Mekit\Bundle\ContactBundle\Entity\Contact';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Contact 1');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Contact();
    }

    /**
     * @param string  $key
     * @param Contact $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');
	    $accountRepo = $this->templateManager
		    ->getEntityRepository('Mekit\Bundle\AccountBundle\Entity\Account');

        switch ($key) {
            case 'Contact 1':
	            $primaryAddress = $this->createAddress(1);
                $entity
                    ->setId(1)
	                ->setOwner($userRepo->getEntity('John Doo'))
	                ->setOrganization($organizationRepo->getEntity('default'))
                    ->setNamePrefix('Sig.')
                    ->setFirstName('John')
                    ->setLastName('Hackett')
                    ->setBirthday(new \DateTime('1974-10-15'))
                    ->setGender('male')
                    ->setDescription('Contatto di prova')
                    ->setSkype('crm-johnhackett')
                    ->setTwitter('crm-johnhackett')
                    ->setFacebook('crm-johnhackett')
                    ->setGooglePlus('https://plus.google.com/98765432109876')
                    ->setLinkedIn('http://www.linkedin.com/in/crm-johnhackett')
                    ->addEmail($this->createEmail('email1@test.com', true))
                    ->addEmail($this->createEmail('email2@test.com'))
	                ->addPhone($this->createPhone("011 123.45.67", true))
	                ->addPhone($this->createPhone("011 123.45.68"))
                    ->addAccount($accountRepo->getEntity('Mekit'))
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
