<?php

namespace Mekit\Bundle\ListBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;


/**
 * Class ListGroupType
 */
class ListGroupType extends AbstractType {
	/**
	 * @var Router
	 */
	protected $router;

	/**
	 * @var NameFormatter
	 */
	protected $nameFormatter;

	/**
	 * @var SecurityFacade
	 */
	protected $securityFacade;


	/**
	 * @param Router         $router
	 * @param NameFormatter  $nameFormatter
	 * @param SecurityFacade $securityFacade
	 */
	public function __construct(Router $router, NameFormatter $nameFormatter, SecurityFacade $securityFacade) {
		$this->nameFormatter = $nameFormatter;
		$this->router = $router;
		$this->securityFacade = $securityFacade;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name', 'text', ['required' => true, 'label' => 'mekit.list.listgroup.name.label'])
			->add('label', 'text', ['required' => true, 'label' => 'mekit.list.listgroup.label.label'])
			->add('description', 'textarea', ['required' => false, 'label' => 'mekit.list.listgroup.description.label'])
			->add('itemPrefix', 'text', ['required' => true, 'label' => 'mekit.list.listgroup.item_prefix.label'])
			->add('emptyValue', 'text', ['required' => false, 'label' => 'mekit.list.listgroup.empty_value.label'])
			->add('required', 'choice', ['required' => true, 'label' => 'mekit.list.listgroup.required.label',
				'choices' => [0 => 'mekit.list.generic.no', 1 => 'mekit.list.generic.yes']]
			);
	}

	/**
	 * @param FormView      $view
	 * @param FormInterface $form
	 * @param array         $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options) {
		if(!$form->isSubmitted()) {
			/** @var ListGroup $entity */
			$entity = $form->getData();
			if ($entity instanceof ListGroup) {
				//if ListGroup already has items, you cannot modify the itemPrefix anymore
				if(!$entity->hasItems()) {
					$form->add('itemPrefix', 'text', ['required' => true, 'label' => 'mekit.list.listgroup.item_prefix.label']);
				} else {
					$form->add('itemPrefix', 'hidden');
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\ListBundle\Entity\ListGroup',
				'intention' => 'listgroup',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_listgroup';
	}
}