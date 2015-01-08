<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Mekit\Bundle\RelationshipBundle\Entity\Repository\ReferenceableElementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ReferenceSelectType2 extends AbstractType {
	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			[
				'autocomplete_alias' => 'referenceable_element_autocomplete_search',
				'configs' => [
					'placeholder' => 'Pick One!',
					'result_template_twig' => 'MekitRelationshipBundle:ReferenceableElement:Autocomplete/result.html.twig',
					'selection_template_twig' => 'MekitRelationshipBundle:ReferenceableElement:Autocomplete/selection.html.twig'
				]
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
		return 'mekit_reference_select_2';
	}
}