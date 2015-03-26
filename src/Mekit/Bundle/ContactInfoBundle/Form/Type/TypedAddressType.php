<?php
namespace Mekit\Bundle\ContactInfoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Oro\Bundle\AddressBundle\Entity\Country;


class TypedAddressType extends AbstractType
{

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		/** hiding useless fields - maybe one day we can find a cleaner solution for this */
		$uselessFields = [
			'namePrefix',
		    'nameSuffix',
			'firstName',
			'middleName',
			'lastName',
		    'organization',
		    'types'
		];
		foreach($uselessFields as $fieldName) {
			if($builder->has($fieldName)) {
				$builder->remove($fieldName);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return 'oro_typed_address';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'mekit_typed_address';
	}
}
