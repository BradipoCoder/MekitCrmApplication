<?php
/**
 * Created by Adam Jakab.
 * Date: 12/11/14
 * Time: 12.32
 */

namespace Mekit\Bundle\ListBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\SecurityBundle\SecurityFacade;

/**
 * Class ListItemType
 */
class ListItemType extends AbstractType {

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
		$builder
			->add('label', 'text', ['required' => true, 'label' => 'mekit.list.label.label']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\ListBundle\Entity\ListItem',
				'intention' => 'listitem',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => false
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_listitem';
	}

}