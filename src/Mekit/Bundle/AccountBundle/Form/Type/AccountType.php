<?php

namespace Mekit\Bundle\AccountBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;

use Mekit\Bundle\AccountBundle\Entity\Account;
//use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ListBundle\Entity\ListItemRepository;


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

			//Type - dynamic list group item from ListBundle
	        ->add(
		        'type',
		        'entity',
		        array(
			        'required'    => true,
			        'label'       => 'mekit.account.type.label',
			        'class'       => 'MekitListBundle:ListItem',
			        'query_builder' => function(ListItemRepository $er) {
				        return $er->getListItemQueryBuilder('ACCOUNT_TYPE');
			        }
		        )
	        )

			//State - dynamic list group item from ListBundle
			->add(
				'state',
				'entity',
				array(
					'required'    => true,
					'label'       => 'mekit.account.state.label',
					'class'       => 'MekitListBundle:ListItem',
					'query_builder' => function(ListItemRepository $er) {
						return $er->getListItemQueryBuilder('ACCOUNT_STATE');
					}
				)
			)

			//Industry - dynamic list group item from ListBundle
			->add(
				'industry',
				'entity',
				array(
					'required'    => true,
					'label'       => 'mekit.account.industry.label',
					'class'       => 'MekitListBundle:ListItem',
					'query_builder' => function(ListItemRepository $er) {
						return $er->getListItemQueryBuilder('ACCOUNT_INDUSTRY');
					}
				)
			)

			//Source - dynamic list group item from ListBundle
			->add(
				'source',
				'entity',
				array(
					'required'    => true,
					'label'       => 'mekit.account.source.label',
					'class'       => 'MekitListBundle:ListItem',
					'query_builder' => function(ListItemRepository $er) {
						return $er->getListItemQueryBuilder('ACCOUNT_SOURCE');
					}
				)
			)

			//email
			->add(
				'emails',
				'oro_email_collection',
				array(
					'label'    => 'mekit.account.emails.label',
					'type'     => 'oro_email',
					'required' => false,
					'options'  => array('data_class' => 'Mekit\Bundle\AccountBundle\Entity\AccountEmail')
				)
			)

			//addresses
			->add(
				'addresses',
				'oro_address_collection',
				array(
					'label'    => '',
					'type'     => 'oro_typed_address',
					'required' => true,
					'options'  => array('data_class' => 'Mekit\Bundle\AccountBundle\Entity\AccountAddress')
				)
			)

			// assigned to (user)
	        ->add(
		        'assignedTo',
		        'oro_user_select',
		        array('required' => false, 'label' => 'mekit.account.assigned_to.label')
	        )

			//phones
			->add(
				'phones',
				'oro_phone_collection',
				array(
					'label'    => 'mekit.account.phones.label',
					'type'     => 'oro_phone',
					'required' => false,
					'options'  => array('data_class' => 'Mekit\Bundle\AccountBundle\Entity\AccountPhone')
				)
			)


            ->add('tags', 'oro_tag_select', ['label' => 'oro.tag.entity_plural_label']);
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