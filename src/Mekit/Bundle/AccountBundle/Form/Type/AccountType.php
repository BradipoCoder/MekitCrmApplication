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

/**
 * Class AccountType
 * @package Mekit\Bundle\AccountBundle\Form\Type
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

			->add('state', 'mekit_account_state', array('required' => true, 'label' => 'mekit.account.state.label', 'data' => 7))

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