<?php
namespace Mekit\Bundle\OpportunityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;

class OpportunityApiType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->addEventSubscriber(new PatchSubscriber());
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'csrf_protection' => false,
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent() {
		return 'mekit_opportunity';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'opportunity';
	}
}