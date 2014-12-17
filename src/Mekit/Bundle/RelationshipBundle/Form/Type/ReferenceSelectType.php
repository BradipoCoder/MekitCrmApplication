<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceSelectType extends AbstractType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add(
				'type',
				'choice',
				array(
					'required' => true,
					'label' => 'TYPE',
					'choices' => [
						'Mekit\Bundle\AccountBundle\Entity\Account' => 'Account',
						'Mekit\Bundle\ContactBundle\Entity\Contact' => 'Contact',
					]
				)
			);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			[
				'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement'
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_reference_select';
	}
}