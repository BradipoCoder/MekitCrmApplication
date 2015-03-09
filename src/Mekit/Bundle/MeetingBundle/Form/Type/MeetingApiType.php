<?php
namespace Mekit\Bundle\MeetingBundle\Form\Type;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MeetingApiType extends MeetingType
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
			'data_class' => 'Mekit\Bundle\MeetingBundle\Entity\Meeting',
			'intention' => 'meeting',
			'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
			'cascade_validation' => true,
			'csrf_protection' => false
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_meeting_api';
	}
}