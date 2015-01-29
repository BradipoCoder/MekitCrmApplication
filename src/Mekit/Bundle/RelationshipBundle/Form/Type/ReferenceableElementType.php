<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Mekit\Bundle\RelationshipBundle\Entity\Manager\ReferenceManager;
use Mekit\Bundle\RelationshipBundle\EventListener\ReferenceableElementTypeListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceableElementType extends AbstractType {
	/** @var  ReferenceManager */
	protected $referenceManager;

	/**
	 * @param ReferenceManager $referenceManager
	 */
	public function __construct(ReferenceManager $referenceManager) {
		$this->referenceManager = $referenceManager;
	}

	/**
	 * All fields to this type will be added dynamically by ReferenceableElementTypeListener
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->addEventSubscriber(new ReferenceableElementTypeListener($this->referenceManager));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_referenceable_element';
	}
}