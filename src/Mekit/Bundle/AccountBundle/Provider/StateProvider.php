<?php
/**
 * Created by Adam Jakab.
 * Date: 05/11/14
 * Time: 11.57
 */

namespace Mekit\Bundle\AccountBundle\Provider;

use Symfony\Component\Translation\TranslatorInterface;


class StateProvider {
	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	/**
	 * @var array
	 */
	protected $choices = array(
		1 => 'Fidelizzato',
		2 => 'Attivo',
		3 => 'Dormiente',
		4 => 'Potenziale',
		5 => 'Negativo',
		6 => 'Ri-attivato',
		7 => 'Nuovo',
		8 => 'Vecchio Dormiente',
		9 => 'Mercato Potenziale'
	);

	/**
	 * @var array
	 */
	protected $translatedChoices;

	/**
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	/**
	 * @return array
	 */
	public function getChoices() {
		if (null === $this->translatedChoices) {
			$this->translatedChoices = array();
			foreach ($this->choices as $name => $label) {
				$this->translatedChoices[$name] = $this->translator->trans($label);
			}
		}

		return $this->translatedChoices;
	}

	/**
	 * @param string $name
	 * @return string
	 * @throws \LogicException
	 */
	public function getLabelByValue($name) {
		$choices = $this->getChoices();
		if (!isset($choices[$name])) {
			throw new \LogicException(sprintf('Unknown option with name "%s"', $name));
		}
		return $choices[$name];
	}
}