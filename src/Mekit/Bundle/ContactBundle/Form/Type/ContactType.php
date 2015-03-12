<?php

namespace Mekit\Bundle\ContactBundle\Form\Type;

use Mekit\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;


/**
 * Class ContactType
 */
class ContactType extends AbstractType {

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
	 * @var boolean
	 */
	private $canViewContact;

	/**
	 * @param Router         $router
	 * @param NameFormatter  $nameFormatter
	 * @param SecurityFacade $securityFacade
	 */
	public function __construct(Router $router, NameFormatter $nameFormatter, SecurityFacade $securityFacade) {
		$this->nameFormatter = $nameFormatter;
		$this->router = $router;
		$this->securityFacade = $securityFacade;
		$this->canViewContact = $this->securityFacade->isGranted('mekit_contact_view');
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		// basic fields
		$builder
			->add('namePrefix', 'text', array('required' => false, 'label' => 'mekit.contact.name_prefix.label'))
			->add('firstName', 'text', array('required' => true, 'label' => 'mekit.contact.first_name.label'))
			/*->add('middleName', 'text', array('required' => false, 'label' => 'mekit.contact.middle_name.label'))*/
			->add('lastName', 'text', array('required' => false, 'label' => 'mekit.contact.last_name.label'))
			/*->add('nameSuffix', 'text', array('required' => false, 'label' => 'mekit.contact.name_suffix.label'))*/
			->add('gender', 'oro_gender', array('required' => false, 'label' => 'mekit.contact.gender.label'))
			->add('birthday', 'oro_date', array('required' => false, 'label' => 'mekit.contact.birthday.label'))
			->add('description', 'textarea', array('required' => false, 'label' => 'mekit.contact.description.label'))
			->add('tags', 'oro_tag_select', ['label' => 'oro.tag.entity_plural_label']);

		//social stuff
		$builder
			->add('skype', 'text', array('required' => false, 'label' => 'mekit.contact.skype.label'))
			->add('twitter', 'text', array('required' => false, 'label' => 'mekit.contact.twitter.label'))
			->add('facebook', 'text', array('required' => false, 'label' => 'mekit.contact.facebook.label'))
			->add('googlePlus', 'text', array('required' => false, 'label' => 'mekit.contact.google_plus.label'))
			->add('linkedIn', 'text', array('required' => false, 'label' => 'mekit.contact.linked_in.label'));


		//dynamic lists from ListBundle
		$builder->add('jobTitle', 'mekit_listitem_select', ['label'=>'mekit.contact.job_title.label', 'configs'=>['group'=>'CONTACT_JOBTITLE']]);

		//users
		$builder->add(
			'users',
			'oro_user_multiselect',
			[
				'required' => false,
				'label' => 'mekit.contact.users.label',
			]
		);

		//accounts
		$builder->add(
			'accounts',
			'mekit_entity_multi_select',
			[
				'required' => false,
				'label' => 'mekit.contact.accounts.label',
				'autocomplete_alias' => 'mekit_account',
				'entity_class' => 'Mekit\Bundle\AccountBundle\Entity\Account',
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
				'required' => false,
				'options' => array('data_class' => 'Mekit\Bundle\ContactInfoBundle\Entity\Address')
			)
		);

		//emails
		$builder->add(
			'emails',
			'oro_email_collection',
			array(
				'label' => 'mekit.contact.emails.label',
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
				'label' => 'mekit.contact.phones.label',
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
				'data_class' => 'Mekit\Bundle\ContactBundle\Entity\Contact',
				'intention' => 'contact',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_contact';
	}
}