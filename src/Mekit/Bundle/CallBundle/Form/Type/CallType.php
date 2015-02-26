<?php

namespace Mekit\Bundle\CallBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\EventBundle\Form\Type\EventType;
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
 * Class CallType
 */
class CallType extends AbstractType {

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
		$this->router = $router;
		$this->nameFormatter = $nameFormatter;
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
			->add('name', 'text', array('required' => true, 'label' => 'mekit.call.name.label'));

		//static list - call direction(in/out)
		$builder->add('direction', 'choice', [
				'required' => true,
				'label' => 'mekit.call.direction.label',
				'expanded' => true,
				'choices' => [
					'in' => 'mekit.call.direction.in.label',
					'out' => 'mekit.call.direction.out.label'
				]
			]
		);

		//dynamic lists from ListBundle(using temporary helper service solution)
		$this->listBundleHelper->addListSelectorToFormBuilder($builder, 'outcome', 'CALL_OUTCOME', 'mekit.call.outcome.label');

		//users
		$builder->add(
			'users',
			'oro_user_multiselect',
			[
				'required' => false,
				'label' => 'mekit.call.users.label',
			]
		);

		//accounts
		$builder->add(
			'accounts',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.call.accounts.label',
				'autocomplete_alias' => 'mekit_account',
				'entity_class' => 'Mekit\Bundle\AccountBundle\Entity\Account',
				'configs' => []
			]
		);

		//contacts
		$builder->add(
			'contacts',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.call.contacts.label',
				'autocomplete_alias' => 'mekit_contact',
				'entity_class' => 'Mekit\Bundle\ContactBundle\Entity\Contact',
				'configs' => []
			]
		);

		//tasks
		$builder->add(
			'tasks',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.call.tasks.label',
				'autocomplete_alias' => 'mekit_task',
				'entity_class' => 'Mekit\Bundle\TaskBundle\Entity\Task',
				'configs' => []
			]
		);

		//meetings
		$builder->add(
			'meetings',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.call.meetings.label',
				'autocomplete_alias' => 'mekit_meeting',
				'entity_class' => 'Mekit\Bundle\MeetingBundle\Entity\Meeting',
				'configs' => []
			]
		);


		//add event form
		$builder->add('event', new EventType($this->router, $this->nameFormatter, $this->securityFacade, $this->listBundleHelper));
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\CallBundle\Entity\Call',
				'intention' => 'call',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_call';
	}
}