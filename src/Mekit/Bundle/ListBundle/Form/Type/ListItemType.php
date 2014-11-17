<?php
/**
 * Created by Adam Jakab.
 * Date: 12/11/14
 * Time: 12.32
 */

namespace Mekit\Bundle\ListBundle\Form\Type;

use Mekit\Bundle\ListBundle\Entity\ListItem;
use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * Class ListItemType
 */
class ListItemType extends AbstractType {

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
			->add('id', 'text', ['required' => true, 'label' => 'mekit.list.id.label'])
			->add('label', 'text', ['required' => true, 'label' => 'mekit.list.label.label']);
	}

	/**
	 * @param FormView      $view
	 * @param FormInterface $form
	 * @param array         $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options) {
		if(!$form->isSubmitted()) {
			/** @var ListItem $entity */
			$entity = $form->getData();
			if ($entity instanceof ListItem) {
				//if ID is defined (existing item) - you cannot modify it anymore - so it will be hidden
				if(!$entity->getID()) {
					$form->add('id', 'text', ['required' => true, 'label' => 'mekit.list.id.label']);
				} else {
					$form->add('id', 'hidden');
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
				'data_class' => 'Mekit\Bundle\ListBundle\Entity\ListItem',
				'intention' => 'listitem',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => false
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_listitem';
	}

}