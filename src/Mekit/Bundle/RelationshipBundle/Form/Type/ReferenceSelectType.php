<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Mekit\Bundle\RelationshipBundle\Entity\Repository\ReferenceableElementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ReferenceSelectType extends AbstractType {
	/**
	 * @var ObjectManager
	 */
	protected $manager;

	/**
	 * @param ObjectManager $manager
	 */
	public function __construct(ObjectManager $manager) {
		$this->manager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add(
				'type',
				'choice',
				[
					'required' => true,
					'empty_value' => 'Choose a type',
					'choices' => [
						'Mekit\Bundle\AccountBundle\Entity\Account' => 'Account',
						'Mekit\Bundle\ContactBundle\Entity\Contact' => 'Contact',
					]
				]
			);

		/** @var ReferenceableElementRepository $repo */
		$repo = $this->manager->getRepository("MekitRelationshipBundle:ReferenceableElement");

		$constraints = [];

		$builder->add(
			'referenced_element',
			'entity',
			[
				'class' => 'Mekit\Bundle\ContactBundle\Entity\Contact',
				'required' => true,
				'label' => 'REF',
				'empty_value' => 'Choose a referenceable element',
				'mapped' => false,
				'query_builder' => $repo->getReferencedElementsQueryBuilderByType('Mekit\Bundle\ContactBundle\Entity\Contact'),
				'constraints'   => $constraints
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			[
				'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement'
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_reference_select';
	}
}