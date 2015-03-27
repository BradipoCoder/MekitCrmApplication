<?php
namespace Mekit\Bundle\WorklogBundle\Form\Type;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorklogApiType extends WorklogType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
		$builder->addEventSubscriber(new PatchSubscriber());
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Mekit\Bundle\WorklogBundle\Entity\Worklog',
			'intention' => 'worklog',
			'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
			'csrf_protection' => false,
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_worklog_api';
	}
}