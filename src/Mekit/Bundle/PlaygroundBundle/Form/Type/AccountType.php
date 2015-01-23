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

		//select referenced for: Account
		$builder->add('extra_field_1', 'referenceable_element_multi_select2', [
			'mapped' => false,
			'required' => false,
			'label' => 'Referenced Accounts',
			'configs' => [
				'entity_name' => 'Mekit\Bundle\AccountBundle\Entity\Account',/*The entity type we want to select*/
				'entity_fields' => ['name', 'vatid'],
				'entity_id' => 0, /* Dunno - maybe we don not have use for this */
			]
		]);

		//select referenced for: Contact
		$builder->add('extra_field_2', 'referenceable_element_multi_select2', [
			'mapped' => false,
			'required' => false,
			'label' => 'Referenced Contacts',
			'configs' => [
				'entity_name' => 'Mekit\Bundle\ContactBundle\Entity\Contact',/*The entity type we want to select*/
				'entity_fields' => ['firstName', 'lastName'],
				'entity_id' => 0, /* Dunno - maybe we don not have use for this */
			]
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