<?php

namespace Mekit\Bundle\EventBundle\Form\Type;

use Mekit\Bundle\EventBundle\Entity\Event;
use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;


/**
 * Class EventType
 */
class EventType extends AbstractType
{

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

		// basic fields
		$builder->add(
				'type', 'hidden', array(
				'required' => true,
				'label' => 'mekit.event.type.label'
			)
			)->add(
				'startDate', 'oro_datetime', array(
				'required' => true,
				'label' => 'mekit.event.start_date.label'
			)
			)->add(
				'endDate', 'oro_datetime', array(
				'required' => false,
				'label' => 'mekit.event.end_date.label'
			)
			)->add(
				'duration', 'number', array(
				'required' => false,
				'label' => 'mekit.event.duration.label'
			)
			)->add(
				'description', 'textarea', array(
				'required' => false,
				'label' => 'mekit.event.description.label'
			)
			);


		//dynamic lists from ListBundle
		$builder->add(
			'state', 'mekit_listitem_select', [
			'label' => 'mekit.event.state.label',
			'configs' => ['group' => 'EVENT_STATE']
		]
		);
		$builder->add(
			'priority', 'mekit_listitem_select', [
			'label' => 'mekit.event.priority.label',
			'configs' => ['group' => 'EVENT_PRIORITY']
		]
		);

		//Set the correct type of the entity bound to the parent form
		$builder->addEventListener(
			FormEvents::POST_SET_DATA, function (FormEvent $event) {
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