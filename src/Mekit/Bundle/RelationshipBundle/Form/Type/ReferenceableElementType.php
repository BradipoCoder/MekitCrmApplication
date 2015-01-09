<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Mekit\Bundle\RelationshipBundle\Helper\FormHelper;
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


class ReferenceableElementType extends AbstractType {
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
	protected $helper;

	/**
	 * @param Router         $router
	 * @param NameFormatter  $nameFormatter
	 * @param SecurityFacade $securityFacade
	 * @param FormHelper $helper
	 */
	public function __construct(Router $router, NameFormatter $nameFormatter, SecurityFacade $securityFacade, FormHelper $helper) {
		$this->nameFormatter = $nameFormatter;
		$this->router = $router;
		$this->securityFacade = $securityFacade;
		$this->helper = $helper;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('id', 'text', ['read_only' => true]);
		$builder->add('type', 'text', ['read_only' => true]);


		//Solutuion #3 (no other types are involved) - skipping altogether ReferenceSelectCollectionType && ReferenceSelectType
		//this works - however it does not allow for type selection
		// adds/removes references
		$builder->add(
			'references',
			'oro_collection',
			[
				'type' => 'entity',
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false,
				'required' => false,
				'handle_primary'       => false,
				'show_form_when_empty' => false,
				'options' =>
				[
					'class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
					'label' => false,
					'empty_value' => 'Choose a referenceable element',
					//'query_builder' => $this->helper->getReferencedElementsQueryBuilderByType('Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement')
				]
			]
		);


		//Set the correct type of the entity bound to the parent form
		$builder->addEventListener(
			FormEvents::POST_SET_DATA,
			function (FormEvent $event) {
				$parentForm = $event->getForm()->getParent();
				if ($parentForm) {
					$parentDataClass = $parentForm->getConfig()->getDataClass();
					$event->getForm()->get("type")->setData($parentDataClass);
				} else {
					throw new \LogicException("No parent form!");
				}
			}
		);


	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
				'intention' => 'referenceable_element',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_referenceable_element';
	}
}