<?php
namespace Mekit\Bundle\TaskBundle\Controller;

use BeSimple\SoapCommon\Type\KeyValue\DateTime;
use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Mekit\Bundle\TaskBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;


/**
 * Class TaskController
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
	 * @AclAncestor("mekit_event_view")
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
	 * @AclAncestor("mekit_event_view")
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
	 *      class="MekitEventBundle:Event"
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
	 *      class="MekitEventBundle:Event"
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
			/** @var ListItemRepository $listItemRepo */
			$listItemRepo = $this->getDoctrine()->getRepository('MekitListBundle:ListItem');

			/** @var Event $event */
			$event = $this->getManagerEvent()->createEntity();
			$event->setStartDate(new \DateTime());
			$event->setState($listItemRepo->getDefaultItemForGroup("EVENT_STATE"));
			$event->setPriority($listItemRepo->getDefaultItemForGroup("EVENT_PRIORITY"));
			$event->setOwner($this->getUser());

			/** @var Task $entity */
			$entity = $this->getManagerTask()->createEntity();

			//assign to current user
			$entity->addUser($this->getUser());

			//set relationship between entity and Event
			$entity->setEvent($event);
			$event->setTask($entity);
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
	 * @AclAncestor("mekit_event_view")
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
	 * This action is used to render the list of tasks associated with the given entity
	 * on the view page of this entity
	 *
	 * @Route(
	 *      "/widget/activity/{entityClass}/{entityId}",
	 *      name="mekit_task_activity_widget"
	 * )
	 * @AclAncestor("mekit_event_view")
	 * @Template(template="MekitTaskBundle:Task/widget:activity.html.twig")
	 * @param string $entityClass
	 * @param mixed $entityId
	 * @return array
	 */
	public function activityAction($entityClass, $entityId) {
		return [
			'entity' => $this->get('oro_entity.routing_helper')->getEntity($entityClass, $entityId)
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManagerTask() {
		return $this->get('mekit_task.task.manager.api');
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManagerEvent() {
		return $this->get('mekit_event.event.manager.api');
	}
}
