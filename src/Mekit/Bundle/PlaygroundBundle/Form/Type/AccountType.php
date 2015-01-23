<?php
namespace Mekit\Bundle\PlaygroundBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


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

		//select referenceable elements
		$builder->add('referenceableElement', 'mekit_referenceable_element', [
			'label' => false,
			'required' => false
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