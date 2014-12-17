<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceSelectCollectionType extends AbstractType {
	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'type' => 'mekit_relationship_reference'
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent() {
		return 'oro_collection';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_reference_select_collection';
	}
}