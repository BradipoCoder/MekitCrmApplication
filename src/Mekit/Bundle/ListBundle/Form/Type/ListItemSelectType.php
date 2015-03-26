<?php
namespace Mekit\Bundle\ListBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\ListBundle\Entity\Repository\ListGroupRepository;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Oro\Bundle\TranslationBundle\Translation\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ListItemSelectType extends AbstractType
{
	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @param EntityManager $entityManager
	 * @param Translator    $translator
	 */
	public function __construct(EntityManager $entityManager, Translator $translator) {
		$this->entityManager = $entityManager;
		$this->translator = $translator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		/** Set default item if no data is set */
		$builder->addEventListener(
			FormEvents::POST_SET_DATA, function (FormEvent $event) {
			$data = $event->getData();
			if (!$data) {
				$form = $event->getForm();
				$formConfig = $form->getConfig();
				$configs = $formConfig->getOption("configs");
				$listGroupName = $configs['group'];
				/** @var ListItemRepository $listItemRepo */
				$listItemRepo = $this->entityManager->getRepository('MekitListBundle:ListItem');
				$data = $listItemRepo->getDefaultItemForGroup($listGroupName);
				if ($data instanceof ListItem) {
					$form->setData($data);
				}
			}
		}
		);

	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options) {
		/** @var ListGroupRepository $listGroupRepo */
		$listGroupRepo = $this->entityManager->getRepository('MekitListBundle:ListGroup');

		$formConfig = $form->getConfig();
		$configs = $formConfig->getOption("configs");
		$listGroupName = $configs['group'];
		$hidden = isset($configs['hidden']) && $configs['hidden'] === true;

		/** @var ListGroup $listGroup */
		$listGroup = $listGroupRepo->findOneBy(["name" => $listGroupName]);
		if (!$listGroup instanceof ListGroup) {
			throw new InvalidConfigurationException(
				'The option "group" set in "configs" key(' . $listGroupName . ') is not an existing list name.'
			);
		}

		$vars = [
			'required' => $listGroup->isRequired(),
			'label' => $hidden ? false : $listGroup->getLabel(),
			'attr' => $hidden ? ['style' => 'display:none'] : [],
			'empty_value' => $listGroup->getEmptyValue()
		];

		$view->vars = array_replace($view->vars, $vars);
	}


	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$defaultConfig = [
			'group' => null
		];

		$resolver->setDefaults(
			[
				'class' => 'MekitListBundle:ListItem',
				'configs' => $defaultConfig
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

					if (empty($result['group'])) {
						throw new InvalidConfigurationException(
							'Option "group" with the name of a list must be set in "configs" key.'
						);
					}

					return $result;
				},
				'query_builder' => function (Options $options) {
					$configs = $options->get("configs");
					$listGroupName = $configs['group'];
					/** @var ListItemRepository $listItemRepo */
					$listItemRepo = $this->entityManager->getRepository('MekitListBundle:ListItem');
					$qb = $listItemRepo->getListItemQueryBuilder($listGroupName);

					return $qb;
				},
				'constraints' => function (Options $options) {
					$configs = $options->get("configs");
					$listGroupName = $configs['group'];
					/** @var ListGroupRepository $listGroupRepo */
					$listGroupRepo = $this->entityManager->getRepository('MekitListBundle:ListGroup');
					/** @var ListGroup $listGroup */
					$listGroup = $listGroupRepo->findOneBy(["name" => $listGroupName]);

					if (!$listGroup instanceof ListGroup) {
						throw new InvalidConfigurationException(
							'The option "group" set in "configs" key(' . $listGroupName
							. ') is not an existing list name.'
						);
					}

					$constraints = [];
					if ($listGroup->isRequired()) {
						$constraints = [
							new NotBlank(["message" => $this->translator->trans("mekit.list.form.required.message")])
						];
					}

					return $constraints;
				}
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent() {
		return 'entity';
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_listitem_select';
	}
}