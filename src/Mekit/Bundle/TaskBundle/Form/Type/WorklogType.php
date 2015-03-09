<?php
namespace Mekit\Bundle\TaskBundle\Form\Type;

use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use Mekit\Bundle\TaskBundle\Entity\Task;


/**
 * Class WorklogType
 */
class WorklogType extends AbstractType {

	/**
	 * @var Router
	 */
	protected $router;


	/**
	 * @param Router $router
	 */
	public function __construct(Router $router) {
		$this->router = $router;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		// basic fields
		$builder
			->add('executionDate', 'oro_date', array('required' => true, 'label' => 'mekit.task.worklog.execution_date.label'))
			->add('duration', 'text', array('required' => true, 'label' => 'mekit.task.worklog.duration.label'))
			->add('description', 'textarea', array('required' => true, 'label' => 'mekit.task.worklog.description.label'))
			->add('owner', 'oro_user_select', array('required' => true, 'label' => 'mekit.task.worklog.owner.label'));

		//task
		$builder->add(
			'task',
			'oro_entity_select',
			[
				'required' => true,
				'label' => 'mekit.task.worklog.task.label',
				'autocomplete_alias' => 'mekit_task',
				'configs' => [
				]
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\TaskBundle\Entity\Worklog',
				'intention' => 'worklog',
				'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
				'cascade_validation' => true
			)
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_worklog';
	}
}