<?php
namespace Mekit\Bundle\AccountBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

//@todo: this class is to be removed
class AccountSelectType extends AbstractType {
	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			[
				'autocomplete_alias' => 'accounts',
				'create_form_route' => 'mekit_account_create',
				'configs' => [
					'placeholder' => 'mekit.account.form.choose_account'
				],
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent() {
		return 'oro_entity_create_or_select_inline';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_account_select';
	}
}
