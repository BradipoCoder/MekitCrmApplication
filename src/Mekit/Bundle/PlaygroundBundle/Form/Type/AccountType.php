<?php
namespace Mekit\Bundle\PlaygroundBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class AccountType extends AbstractType {
	/**
	 *
	 */
	public function __construct() {}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', ['required' => true, 'label' => 'mekit.account.name.label']);

		//referenceable_element_multi_select2
		$builder->add('extra_field', 'referenceable_element_multi_select2', [
			'mapped' => false,
			'required' => false,
			'label' => 'EXTRA FIELD',
			'entity_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement'
		]);

	}


		/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\AccountBundle\Entity\Account',
				'intention' => 'account',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_playground_account';
	}
}