<?php
namespace Mekit\Bundle\ContactInfoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AddressType extends AbstractType
{

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		/** removing useless fields - maybe one day we can find a cleaner solution for this */
		$uselessFields = [
			'namePrefix',
		    'nameSuffix',
			'firstName',
			'middleName',
			'lastName',
		    'organization'
		];
		foreach($uselessFields as $fieldName) {
			if($builder->has($fieldName)) {
				$builder->remove($fieldName);
			}
		}
		$builder->add('primary', 'checkbox');
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\ContactInfoBundle\Entity\Address',
				'intention' => 'address',
				'extra_fields_message' => 'Address Form - This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return 'oro_address';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'mekit_address';
	}
}
