<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;


class ReferenceableElementType extends AbstractType {
	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('id', 'text', ['read_only' => true]);
		$builder->add('type', 'text', ['read_only' => true]);

		//Set the correct type of the entity bound to the parent form
		$builder->addEventListener(
			FormEvents::POST_SET_DATA,
			function (FormEvent $event) {
				$parentForm = $event->getForm()->getParent();
				if ($parentForm) {
					$parentDataClass = $parentForm->getConfig()->getDataClass();
					$event->getForm()->get("type")->setData($parentDataClass);
				} else {
					throw new \LogicException("No parent form!");
				}
			}
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
				'intention' => 'referenceable_element',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_referenceable_element';
	}
}