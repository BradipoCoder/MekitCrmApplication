<?php
namespace Mekit\Bundle\TaskBundle\Controller;

use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Mekit\Bundle\TaskBundle\Entity\Task;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class TaskController
 */
class TaskController extends Controller
{
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
	 *      permission="VIEW",
	 *      class="MekitTaskBundle:Task"
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
		$entity = $this->initTaskEntity();
		$formAction = $this->get('oro_entity.routing_helper')->generateUrlByRequest(
				'mekit_task_create', $this->getRequest()
			);

		return $this->update($entity, $formAction);
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
		$formAction = $this->get('router')->generate('mekit_task_update', ['id' => $entity->getId()]);

		return $this->update($entity, $formAction);
	}

	/**
	 * @param Task $entity
	 * @param string $formAction
	 * @return array
	 */
	protected function update(Task $entity, $formAction) {
		$saved = false;
		$isWidget = ($this->getRequest()->get('_widgetContainer', false) != false);
		$formHandler = (!$isWidget ? $this->get('mekit_task.form.handler.task') : $this->get(
			'mekit_task.form.handler.task.api'
		));

		if ($formHandler->process($entity)) {
			if (!$isWidget) {
				$this->get('session')->getFlashBag()->add(
					'success', $this->get('translator')->trans('mekit.task.controller.saved.message')
				);

				return $this->get('oro_ui.router')->redirectAfterSave(
					array(
						'route' => 'mekit_task_update',
						'parameters' => array('id' => $entity->getId())
					), array(
						'route' => 'mekit_task_view',
						'parameters' => array('id' => $entity->getId())
					)
				);
			}
			$saved = true;
		}

		return array(
			'entity' => $entity,
			'saved' => $saved,
			'form' => $formHandler->getForm()->createView(),
			'formAction' => $formAction,
		);
	}


	/**
	 * @return Task
	 */
	protected function initTaskEntity() {
		/** @var Event $event */
		$event = $this->getEventManager()->createEntity();
		$event->setStartDate(new \DateTime());

		/** @var Task $entity */
		$entity = $this->getTaskManager()->createEntity();
		$entity->setOwner($this->getUser());
		$entity->addUser($this->getUser());

		//set relationship between Task and Event
		$entity->setEvent($event);
		$event->setTask($entity);

		return ($entity);
	}


	/**
	 * @Route(
	 *      "/widget/info/{id}",
	 *      name="mekit_task_widget_info",
	 *      requirements={"id"="\d+"},
	 *      options={"expose"="true"}
	 * )
	 * @AclAncestor("mekit_task_view")
	 * @Template(template="MekitTaskBundle:Task/widget:info.html.twig")
	 * @param Task $entity
	 * @return array
	 */
	public function infoAction(Task $entity) {
		return [
			'entity' => $entity
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getTaskManager() {
		return $this->get('mekit_task.task.manager.api');
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getEventManager() {
		return $this->get('mekit_event.event.manager.api');
	}
}
