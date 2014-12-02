<?php

namespace Mekit\Bundle\EventBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;

use Mekit\Bundle\ListBundle\Helper\FormHelper;
use Mekit\Bundle\ListBundle\Entity\ListGroup;

use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;


/**
 * Class EventType
 */
class EventType extends AbstractType {

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
	 * @var FormHelper
	 */
	protected $listBundleHelper;

	/**
	 * @param Router         $router
	 * @param NameFormatter  $nameFormatter
	 * @param SecurityFacade $securityFacade
	 * @param FormHelper $listBundleHelper - temporary solution!
	 */
	public function __construct(Router $router, NameFormatter $nameFormatter, SecurityFacade $securityFacade, FormHelper $listBundleHelper) {
		$this->nameFormatter = $nameFormatter;
		$this->router = $router;
		$this->securityFacade = $securityFacade;
		$this->listBundleHelper = $listBundleHelper;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		// basic fields
		$builder
			->add('name', 'text', array('required' => true, 'label' => 'mekit.event.name.label'))
			->add('type', 'hidden', array('required' => true, 'label' => 'mekit.event.type.label'))
			->add('startDate', 'oro_datetime', array('required' => true, 'label' => 'mekit.event.start_date.label'))
			->add('endDate', 'oro_datetime', array('required' => false, 'label' => 'mekit.event.end_date.label'))
			->add('duration', 'number', array('required' => false, 'label' => 'mekit.event.duration.label'));



		//dynamic lists from ListBundle(using temporary helper service solution)
		$this->listBundleHelper->addListSelectorToFormBuilder($builder, 'state', 'EVENT_STATE', 'mekit.event.state.label');
		$this->listBundleHelper->addListSelectorToFormBuilder($builder, 'priority', 'EVENT_PRIORITY', 'mekit.event.priority.label');


		//assigned to (user)
//		$builder->add(
//			'assignedTo',
//			'oro_user_select',
//			array('required' => false, 'label' => 'mekit.contact.assigned_to.label')
//		);


		//Set the correct type of the entity bound to the parent form
		$builder->addEventListener(
			FormEvents::POST_SET_DATA,
			function (FormEvent $event) {
				$parentForm = $event->getForm()->getParent();
				if ($parentForm) {
					$parentDataClass = $parentForm->getConfig()->getDataClass();
					$event->getForm()->get("type")->setData($parentDataClass);
				} else {
					throw new \LogicException("No parent form!");
				}
			}
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\EventBundle\Entity\Event',
				'intention' => 'event',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_event';
	}
}