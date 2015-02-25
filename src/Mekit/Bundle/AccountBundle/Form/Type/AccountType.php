<?php
namespace Mekit\Bundle\AccountBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Mekit\Bundle\ListBundle\Helper\FormHelper;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;

use Mekit\Bundle\AccountBundle\Entity\Account;
//use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Symfony\Component\Validator\Constraints\NotBlank;


/**
 * Class AccountType
 */
class AccountType extends AbstractType {

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


		$builder
			->add('name', 'text', ['required' => true, 'label' => 'mekit.account.name.label'])
			->add('vatid', 'text', ['required' => false, 'label' => 'mekit.account.vatid.label'])
			->add('nin', 'text', ['required' => false, 'label' => 'mekit.account.nin.label'])
			->add('fax', 'text', ['required' => false, 'label' => 'mekit.account.fax.label'])
			->add('website', 'text', ['required' => false, 'label' => 'mekit.account.website.label'])
			->add('description', 'textarea', ['required' => false, 'label' => 'mekit.account.description.label'])
			->add('tags', 'oro_tag_select', ['label' => 'oro.tag.entity_plural_label']);


		//dynamic lists from ListBundle(using temporary helper service solution)
		$this->listBundleHelper->addListSelectorToFormBuilder($builder, 'type', 'ACCOUNT_TYPE', 'mekit.account.type.label');
		$this->listBundleHelper->addListSelectorToFormBuilder($builder, 'state', 'ACCOUNT_STATE', 'mekit.account.state.label');
		$this->listBundleHelper->addListSelectorToFormBuilder($builder, 'industry', 'ACCOUNT_INDUSTRY', 'mekit.account.industry.label');
		$this->listBundleHelper->addListSelectorToFormBuilder($builder, 'source', 'ACCOUNT_SOURCE', 'mekit.account.source.label');


		//contacts
		$builder->add(
			'contacts',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.account.contacts.label',
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
				'label' => 'mekit.account.tasks.label',
				'autocomplete_alias' => 'mekit_task',
				'entity_class' => 'Mekit\Bundle\TaskBundle\Entity\Task',
				'configs' => [
					/*'result_template' => '<%= highlight(_.escape(event.name)) %>',
					'select_template' => '<%= _.escape(event.name) %>'*/
				]
			]
		);

		//meetings
		$builder->add(
			'meetings',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.account.meetings.label',
				'autocomplete_alias' => 'mekit_meeting',
				'entity_class' => 'Mekit\Bundle\MeetingBundle\Entity\Meeting',
				'configs' => []
			]
		);

		//calls
		$builder->add(
			'calls',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.account.calls.label',
				'autocomplete_alias' => 'mekit_call',
				'entity_class' => 'Mekit\Bundle\CallBundle\Entity\Call',
				'configs' => []
			]
		);

		//addresses
		$builder->add(
			'addresses',
			'oro_address_collection',
			array(
				'label' => '',
				'type' => 'oro_typed_address',
				'required' => true,
				'options' => array('data_class' => 'Mekit\Bundle\ContactInfoBundle\Entity\Address')
			)
		);

		//emails
		$builder->add(
			'emails',
			'oro_email_collection',
			array(
				'label' => 'mekit.account.emails.label',
				'type' => 'oro_email',
				'required' => false,
				'options' => array('data_class' => 'Mekit\Bundle\ContactInfoBundle\Entity\Email')
			)
		);

		//phones
		$builder->add(
			'phones',
			'oro_phone_collection',
			array(
				'label' => 'mekit.account.phones.label',
				'type' => 'oro_phone',
				'required' => false,
				'options' => array('data_class' => 'Mekit\Bundle\ContactInfoBundle\Entity\Phone')
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\AccountBundle\Entity\Account',
				'intention' => 'account',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_account';
	}
}