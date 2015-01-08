<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Mekit\Bundle\RelationshipBundle\Entity\Repository\ReferenceableElementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mekit\Bundle\RelationshipBundle\Helper\FormHelper;

class ReferenceSelectType extends AbstractType {
	/**
	 * @var FormHelper
	 */
	protected $helper;

	/**
	 * @param FormHelper $helper
	 */
	public function __construct(FormHelper $helper) {
		$this->helper = $helper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder->add(
				'entity_type',
				'choice',
				[
					'required' => true,
					'empty_value' => 'Choose a type',
					'mapped' => false,
					'choices' => [
						'Mekit\Bundle\AccountBundle\Entity\Account' => 'Account',
						'Mekit\Bundle\ContactBundle\Entity\Contact' => 'Contact',
					]
				]
			);

		$constraints = [];
		$builder->add(
			'referenced_item',
			'entity',
			[
				'class' => 'Mekit\Bundle\ContactBundle\Entity\Contact',//dynamic
				//'required' => false,
				'mapped' => false,
				'label' => 'Referenceable Element',
				'empty_value' => 'Choose a referenceable element',
				'query_builder' => $this->helper->getReferencedElementsQueryBuilderByType('Mekit\Bundle\ContactBundle\Entity\Contact'),//dynamic
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
				/*'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement'*/
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