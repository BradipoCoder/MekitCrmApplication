<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;

/**
 * Class RelationshipAssignmentType
 * This type is used in dialog widget when assigning referenceable elements with the aid of datagrid
 * bound to these unmapped fields
 */
class RelationshipAssignmentType extends AbstractType {
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$this->buildFields($builder, $options);
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	protected function buildFields(FormBuilderInterface $builder, array $options) {
		$builder->add(
			'appendElements',
			'oro_entity_identifier',
			array(
				'class'    => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
				'required' => false,
				'mapped'   => false,
				'multiple' => true,
			)
		);
		$builder->add(
			'removeElements',
			'oro_entity_identifier',
			array(
				'class'    => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
				'required' => false,
				'mapped'   => false,
				'multiple' => true,
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
				'intention' => 'relationship',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"'
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_relationship_assign';
	}
}