<?php
namespace Mekit\Bundle\AccountBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
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
		$builder
			->add('name', 'text', ['required' => true, 'label' => 'mekit.account.name.label'])
			->add('vatid', 'text', ['required' => false, 'label' => 'mekit.account.vatid.label'])
			->add('nin', 'text', ['required' => false, 'label' => 'mekit.account.nin.label'])
			->add('fax', 'text', ['required' => false, 'label' => 'mekit.account.fax.label'])
			->add('website', 'text', ['required' => false, 'label' => 'mekit.account.website.label'])
			->add('description', 'textarea', ['required' => false, 'label' => 'mekit.account.description.label'])
			->add('tags', 'oro_tag_select', ['label' => 'oro.tag.entity_plural_label']);

		//dynamic lists from ListBundle
		$builder->add('type', 'mekit_listitem_select', ['label'=>'mekit.account.type.label', 'configs'=>['group'=>'ACCOUNT_TYPE']]);
		$builder->add('state', 'mekit_listitem_select', ['label'=>'mekit.account.state.label', 'configs'=>['group'=>'ACCOUNT_STATE']]);
		$builder->add('industry', 'mekit_listitem_select', ['label'=>'mekit.account.industry.label', 'configs'=>['group'=>'ACCOUNT_INDUSTRY']]);
		$builder->add('source', 'mekit_listitem_select', ['label'=>'mekit.account.source.label', 'configs'=>['group'=>'ACCOUNT_SOURCE']]);

		//users
		$builder->add(
			'users',
			'oro_user_multiselect',
			[
				'required' => false,
				'label' => 'mekit.account.users.label',
			]
		);

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

		//addresses
		$builder->add(
			'addresses',
			'oro_address_collection',
			array(
				'label' => '',
				'type' => 'mekit_typed_address',
				'required' => false,
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