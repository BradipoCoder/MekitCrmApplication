<?php
namespace Mekit\Bundle\ProjectBundle\Form\Type;

use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ListBundle\Helper\FormHelper;
use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * Class ProjectType
 */
class ProjectType extends AbstractType {

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
	 * @var boolean
	 */
	private $canViewContact;

	/**
	 * @param Router         $router
	 * @param NameFormatter  $nameFormatter
	 * @param SecurityFacade $securityFacade
	 * @param FormHelper     $listBundleHelper - temporary solution!
	 */
	public function __construct(Router $router, NameFormatter $nameFormatter, SecurityFacade $securityFacade, FormHelper $listBundleHelper) {
		$this->nameFormatter = $nameFormatter;
		$this->router = $router;
		$this->securityFacade = $securityFacade;
		$this->listBundleHelper = $listBundleHelper;
		$this->canViewContact = $this->securityFacade->isGranted('mekit_contact_view');
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		// basic fields
		$builder
			->add('name', 'text', array('required' => true, 'label' => 'mekit.project.name.label'))
			->add('description', 'textarea', array('required' => false, 'label' => 'mekit.contact.description.label'))
			->add('tags', 'oro_tag_select', ['label' => 'oro.tag.entity_plural_label']);

		//users
		$builder->add(
			'users',
			'oro_user_multiselect',
			[
				'required' => false,
				'label' => 'mekit.project.users.label',
			]
		);

//		//accounts
//		$builder->add(
//			'accounts',
//			'mekit_entity_multi_select',
//			[
//				'required' => false,
//				'label' => 'mekit.contact.accounts.label',
//				'autocomplete_alias' => 'mekit_account',
//				'entity_class' => 'Mekit\Bundle\AccountBundle\Entity\Account',
//				'configs' => []
//			]
//		);
//
//		//tasks
//		$builder->add(
//			'tasks',
//			'mekit_entity_multi_select',
//			[
//				'required' => false,
//				'label' => 'mekit.contact.tasks.label',
//				'autocomplete_alias' => 'mekit_task',
//				'entity_class' => 'Mekit\Bundle\TaskBundle\Entity\Task',
//				'configs' => []
//			]
//		);
//
//		//meetings
//		$builder->add(
//			'meetings',
//			'mekit_entity_multi_select',
//			[
//				'required' => false,
//				'label' => 'mekit.contact.meetings.label',
//				'autocomplete_alias' => 'mekit_meeting',
//				'entity_class' => 'Mekit\Bundle\MeetingBundle\Entity\Meeting',
//				'configs' => []
//			]
//		);
//
//		//calls
//		$builder->add(
//			'calls',
//			'mekit_entity_multi_select',
//			[
//				'required' => false,
//				'label' => 'mekit.contact.calls.label',
//				'autocomplete_alias' => 'mekit_call',
//				'entity_class' => 'Mekit\Bundle\CallBundle\Entity\Call',
//				'configs' => []
//			]
//		);

	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\ProjectBundle\Entity\Project',
				'intention' => 'project',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_project';
	}
}