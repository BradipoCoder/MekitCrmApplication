<?php
namespace Mekit\Bundle\FormBundle\Form\Type;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\FormBundle\Form\DataTransformer\EntitiesToIdsTransformer;


class ReferenceableElementMultiSelect2 extends AbstractType {
	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) {
				$value = $event->getData();
				if (empty($value)) {
					$event->setData(array());
				}
			}
		);
		$builder->addModelTransformer(
			new EntitiesToIdsTransformer($this->entityManager, $options['entity_class'])
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'autocomplete_alias' => 'referenceable_element_select',
				'configs' => array(
					'multiple' => true,
					'width' => '400px',
					'placeholder' => 'mekit.form.choose_value',
					'allowClear' => true,
					'result_template_twig' => 'OroUserBundle:User:Autocomplete/result.html.twig',
					'selection_template_twig' => 'OroUserBundle:User:Autocomplete/selection.html.twig',
				)
			)
		);
	}


	/**
	 * @param string $entityClass
	 *
	 * @return EntityToIdTransformer
	 */
	public function createDefaultTransformer($entityClass) {
		return $value = new EntityToIdTransformer($this->entityManager, $entityClass);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent() {
		return 'oro_jqueryselect2_hidden';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'referenceable_element_multi_select2';
	}
}
