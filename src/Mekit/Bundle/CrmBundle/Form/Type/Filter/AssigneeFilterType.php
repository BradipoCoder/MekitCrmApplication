<?php
namespace Mekit\Bundle\CrmBundle\Form\Type\Filter;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\FilterBundle\Form\Type\Filter\AbstractChoiceType;
use Oro\Bundle\FilterBundle\Form\Type\Filter\FilterType;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AssigneeFilterType extends AbstractChoiceType
{
	const TYPE_CONTAINS     = 1;
	const TYPE_NOT_CONTAINS = 2;
	const NAME              = 'mekit_type_assignee_filter';

	/** @var  EntityManager */
	protected $entityManager;

	/**
	 * @param EntityManager $entityManager
	 * @param TranslatorInterface $translator
	 */
	public function __construct(EntityManager $entityManager, TranslatorInterface $translator)
	{
		$this->entityManager = $entityManager;
		parent::__construct($translator);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return self::NAME;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent()
	{
		return FilterType::NAME;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$choices = array(
			self::TYPE_CONTAINS     => $this->translator->trans('oro.filter.form.label_type_contains'),
			self::TYPE_NOT_CONTAINS => $this->translator->trans('oro.filter.form.label_type_not_contains'),
		);

		$resolver->setDefaults(
			array(
				'field_type'       => 'choice',
				'field_options'    => array(
					'multiple' => true,
					'choices'  => $this->getUsersChoices()
				),
				'operator_choices' => $choices,
				'populate_default' => false,
				'default_value'    => null,
				'null_value'       => null
			)
		);
	}

	/**
	 * @return array
	 */
	protected function getUsersChoices() {
		$choices = [];
		//$criteria = ["enabled"=>true];//this should be optional - for now all
		$criteria = [];
		$users = $this->entityManager->getRepository("OroUserBundle:User")
			->findBy($criteria,["lastName"=>"ASC","firstName"=>"ASC"]);

		if($users) {
			/** @var User $user */
			foreach($users as $user) {
				$choices[$user->getId()] = $user->getLastName() . " " . $user->getFirstName();
			}
		}
		return $choices;
	}

	/**
	 * {@inheritDoc}
	 */
	public function finishView(FormView $view, FormInterface $form, array $options)
	{
		parent::finishView($view, $form, $options);
		if (isset($options['populate_default'])) {
			$view->vars['populate_default'] = $options['populate_default'];
			$view->vars['default_value']    = $options['default_value'];
		}
		if (!empty($options['null_value'])) {
			$view->vars['null_value'] = $options['null_value'];
		}
	}
}