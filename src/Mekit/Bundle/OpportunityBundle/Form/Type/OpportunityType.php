<?php
namespace Mekit\Bundle\OpportunityBundle\Form\Type;

use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * Class OpportunityType
 */
class OpportunityType extends AbstractType
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
		$builder
			->add('name', 'text', array('required' => true, 'label' => 'mekit.opportunity.name.label'))
			->add('description', 'textarea', array('required' => false, 'label' => 'mekit.opportunity.description.label'))
			->add('amount', 'oro_money', array('required' => true, 'label' => 'mekit.opportunity.amount.label'))
			->add('probability', 'oro_percent', array('required' => true, 'label' => 'mekit.opportunity.probability.label'));

		//dynamic lists from ListBundle
		$builder->add('state', 'mekit_listitem_select', [
				'configs'=>['group'=>'OPPORTUNITY_STATE', 'hidden' => true]
			]
		);

		//account
		$builder->add(
			'account',
			'oro_jqueryselect2_hidden',
			[
				'required' => true,
				'label' => 'mekit.opportunity.account.label',
				'autocomplete_alias' => 'mekit_account',
				'configs' => []
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\OpportunityBundle\Entity\Opportunity',
				'intention' => 'opportunity',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_opportunity';
	}
}