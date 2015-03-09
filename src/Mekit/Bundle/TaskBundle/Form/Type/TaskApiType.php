<?php
namespace Mekit\Bundle\TaskBundle\Form\Type;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskApiType extends TaskType
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
			'data_class' => 'Mekit\Bundle\TaskBundle\Entity\Task',
			'intention' => 'task',
			'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
			'cascade_validation' => true,
			'csrf_protection' => false
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_task_api';
	}
}