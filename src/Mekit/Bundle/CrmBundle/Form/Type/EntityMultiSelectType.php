<?php
namespace Mekit\Bundle\CrmBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProviderInterface;
use Oro\Bundle\FormBundle\Autocomplete\SearchRegistry;
use Oro\Bundle\FormBundle\Form\DataTransformer\EntitiesToIdsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EntityMultiSelectType extends AbstractType {
	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * @var SearchRegistry
	 */
	protected $searchRegistry;

	/**
	 * @var ConfigProviderInterface
	 */
	protected $configProvider;

	/**
	 * @param EntityManager           $entityManager
	 * @param SearchRegistry          $registry
	 * @param ConfigProviderInterface $configProvider
	 */
	public function __construct(
		EntityManager $entityManager,
		SearchRegistry $registry,
		ConfigProviderInterface $configProvider
	) {
		$this->entityManager = $entityManager;
		$this->searchRegistry = $registry;
		$this->configProvider = $configProvider;
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
			new EntitiesToIdsTransformer($this->entityManager, $options['entity_class'])
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$defaultConfig = [
			'multiple' => true,
			'placeholder' => 'oro.form.choose_value',
			'minimumInputLength' => 1
			/*if you do not set templates autocomplete will use all values automatically*/
			/*'result_template' => '<%= highlight(_.escape(name)) %>',*/
			/*'select_template' => '<%= _.escape(name) %>'*/
		];

		$resolver
			->setDefaults(
				[
					'empty_value' => '',
					'empty_data' => null,
					'data_class' => null,
					'entity_class' => null,
					'configs' => $defaultConfig,
					'converter' => null,
					'autocomplete_alias' => null,
					'excluded' => null,
					'random_id' => true,
					'error_bubbling' => false,
				]
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
						if (empty($result['properties'])) {
							$searchHandler = $this->searchRegistry->getSearchHandler($autoCompleteAlias);
							$result['properties'] = $searchHandler->getProperties();
						}
						if (empty($result['route_name'])) {
							$result['route_name'] = 'oro_form_autocomplete_search';
						}
						if (empty($result['extra_config'])) {
							$result['extra_config'] = 'autocomplete';
						}
					}

					if (!array_key_exists('route_parameters', $result)) {
						$result['route_parameters'] = [];
					}

					if (empty($result['route_name'])) {
						throw new InvalidConfigurationException(
							'Option "autocomplete_alias" or "configs[route_name]" must be set.'
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
		return 'mekit_entity_multi_select';
	}
}