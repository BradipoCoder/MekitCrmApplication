<?php
/**
 * Created by Adam Jakab.
 * Date: 05/11/14
 * Time: 12.28
 */

namespace Mekit\Bundle\AccountBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

use Mekit\Bundle\AccountBundle\Provider\StateProvider;


class StateType extends AbstractType {
	const NAME = 'mekit_account_state';

	/**
	 * @var StateProvider
	 */
	protected $stateProvider;

	/**
	 * @param StateProvider $stateProvider
	 */
	public function __construct(StateProvider $stateProvider) {
		$this->stateProvider = $stateProvider;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return self::NAME;
	}

	/**
	 * @return string
	 */
	public function getParent() {
		return 'choice';
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'choices' => $this->stateProvider->getChoices(),
				'multiple' => false,
				'expanded' => false,
				/*'empty_value' => 'mekit.account.form.choose_state'*/
			)
		);
	}
}