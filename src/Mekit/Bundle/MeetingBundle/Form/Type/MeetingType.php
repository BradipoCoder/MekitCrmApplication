<?php

namespace Mekit\Bundle\MeetingBundle\Form\Type;

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
use Mekit\Bundle\MeetingBundle\Entity\Meeting;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;


/**
 * Class MeetingType
 */
class MeetingType extends AbstractType {

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
			->add('name', 'text', array('required' => true, 'label' => 'mekit.meeting.name.label'));

		//users
		$builder->add(
			'users',
			'oro_user_multiselect',
			[
				'required' => false,
				'label' => 'mekit.meeting.users.label',
			]
		);

		//accounts
		$builder->add(
			'accounts',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.meeting.accounts.label',
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
				'label' => 'mekit.meeting.contacts.label',
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
				'label' => 'mekit.meeting.tasks.label',
				'autocomplete_alias' => 'mekit_task',
				'entity_class' => 'Mekit\Bundle\TaskBundle\Entity\Task',
				'configs' => []
			]
		);

		//calls
		$builder->add(
			'calls',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.meeting.calls.label',
				'autocomplete_alias' => 'mekit_call',
				'entity_class' => 'Mekit\Bundle\CallBundle\Entity\Call',
				'configs' => []
			]
		);

		//projects
		$builder->add(
			'projects',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.meeting.projects.label',
				'autocomplete_alias' => 'mekit_project',
				'entity_class' => 'Mekit\Bundle\ProjectBundle\Entity\Project',
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
				'data_class' => 'Mekit\Bundle\MeetingBundle\Entity\Meeting',
				'intention' => 'meeting',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_meeting';
	}
}