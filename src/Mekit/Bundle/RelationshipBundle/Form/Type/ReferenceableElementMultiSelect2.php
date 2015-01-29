<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Mekit\Bundle\RelationshipBundle\Form\DataTransformer\ReferenceableEntitiesToIdsTransformer;

//use Oro\Bundle\UserBundle\Form\Type\UserMultiSelectType; //model used
//use Oro\Bundle\FormBundle\Form\Type\OroJquerySelect2HiddenType; //model used
//
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
	public function buildForm(FormBuilderInterface $builder, array $options) {

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
			new ReferenceableEntitiesToIdsTransformer($this->entityManager, $options['configs']['entity_name'])
		);

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$defaultConfig = [
			'extra_config' => 'autocomplete_referenceable_element',/* triggers usage of block: "oro_combobox_dataconfig_autocomplete_referenceable_element" */
			'entity_name' => null,
			'entity_fields' => [],
			'entity_id' => null,
			'multiple' => true,
			'width' => '900px',
			'placeholder' => 'mekit.relationship.choose_value.label',
			'allowClear' => true,
			'minimumInputLength' => 1,
			'result_template_twig' => 'MekitRelationshipBundle:Autocomplete:ReferenceableElementMultiSelect2/result.html.twig',
			'selection_template_twig' => 'MekitRelationshipBundle:Autocomplete:ReferenceableElementMultiSelect2/selection.html.twig'

		];

		$resolver->setDefaults(
			array(
				'autocomplete_alias' => 'referenceable_element_select',
				'entity_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
				'attr' => ['class' => 'referenceable_element_multi_select2'],
				'configs' => $defaultConfig
			)
		);

		$this->setConfigsNormalizer($resolver, $defaultConfig);
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 * @param array                    $defaultConfig
	 */
	protected function setConfigsNormalizer(OptionsResolverInterface $resolver, array $defaultConfig) {
		$resolver->setNormalizers(
			[
				'configs' => function (Options $options, $configs) use ($defaultConfig) {
					$result = array_replace_recursive($defaultConfig, $configs);

					if ($autoCompleteAlias = $options->get('autocomplete_alias')) {
						$result['autocomplete_alias'] = $autoCompleteAlias;
						if (empty($result['route_name'])) {
							$result['route_name'] = 'oro_form_autocomplete_search';
						}
						//force 'extra_config'
						$result['extra_config'] = 'autocomplete_referenceable_element';
					}

					if (!array_key_exists('route_parameters', $result)) {
						$result['route_parameters'] = [];
					}

					if (empty($result['route_name'])) {
						throw new InvalidConfigurationException(
							'Option "configs[route_name]" must be set.'
						);
					}

					if (empty($result['entity_name'])) {
						throw new InvalidConfigurationException(
							'Option "configs[entity_name]" must be set.'
						);
					}
					//@todo: we need to check if this entity is referenceable


					if (!is_array($result['entity_fields']) || !count($result['entity_fields'])) {
						throw new InvalidConfigurationException(
							'Option "configs[entity_fields]" must be set and must be an array of valid fields of the entity indicated in the "configs[entity_name]" option.'
						);
					}

					if (in_array("id", $result['entity_fields'])) {
						throw new InvalidConfigurationException(
							'Option "configs[entity_fields]" MUST NOT specify field "id"! Remove it and you\'ll be fine!'
						);
					}

					return $result;
				}
			]
		);
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
