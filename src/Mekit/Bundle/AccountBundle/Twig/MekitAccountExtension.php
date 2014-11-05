<?php

namespace Mekit\Bundle\AccountBundle\Twig;

use Mekit\Bundle\AccountBundle\Provider\StateProvider;

class MekitAccountExtension extends \Twig_Extension {
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
	 * Returns a list of functions to add to the existing list.
	 *
	 * @return array An array of functions
	 */
	public function getFunctions() {
		return array(
			'mekit_account_state' => new \Twig_Function_Method($this, 'getStateLabel'),
		);
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function getStateLabel($name) {
		if (!$name) {
			return null;
		}

		return $this->stateProvider->getLabelByValue($name);
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string
	 */
	public function getName() {
		return 'account_extension';
	}
}
