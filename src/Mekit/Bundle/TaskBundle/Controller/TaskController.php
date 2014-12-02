<?php
namespace Mekit\Bundle\TaskBundle\Controller;

use Mekit\Bundle\TaskBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;


/**
 * Class TaskController
 * todo: all Acl resources should be of event - !!!
 */
class TaskController extends Controller {
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_task_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_task_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
			'entity_class' => $this->container->getParameter('mekit_task.task.entity.class')
		);
	}

	/**
	 * @Route("/view/{id}", name="mekit_task_view", requirements={"id"="\d+"})
	 * @Template
	 * @Acl(
	 *      id="mekit_task_view",
	 *      type="entity",
	 *      class="MekitTaskBundle:Task",
	 *      permission="VIEW"
	 * )
	 * @param Task $entity
	 * @return array
	 */
	public function viewAction(Task $entity) {
		return [
			'entity' => $entity
		];
	}

	/**
	 * @Route("/create", name="mekit_task_create")
	 * @Acl(
	 *      id="mekit_task_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitTaskBundle:Task"
	 * )
	 * @Template("MekitTaskBundle:Task:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_task_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_task_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitTaskBundle:Task"
	 * )
	 * @Template()
	 * @param Task $entity
	 * @return array
	 */
	public function updateAction(Task $entity) {
		return $this->update($entity);
	}

	/**
	 * @param Task $entity
	 * @return array
	 */
	protected function update(Task $entity = null) {
		if (!$entity) {
			/** @var Task $entity */
			$entity = $this->getManager()->createEntity();

			//assign to current user
			//$entity->setAssignedTo($this->getUser());
		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_task.form.task'),
			function (Task $entity) {
				return array(
					'route' => 'mekit_task_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Task $entity) {
				return array(
					'route' => 'mekit_task_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.task.controller.saved.message'),
			$this->get('mekit_task.form.handler.task')
		);
	}



	/**
	 * @Route("/widget/info/{id}", name="mekit_task_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_task_view")
	 * @Template(template="MekitTaskBundle:Task/widget:info.html.twig")
	 * @param Task $task
	 * @return array
	 */
	public function infoAction(Task $task) {
		return [
			'entity' => $task
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManager() {
		return $this->get('mekit_task.task.manager.api');
	}
}
