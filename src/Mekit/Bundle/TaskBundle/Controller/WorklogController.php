<?php
namespace Mekit\Bundle\TaskBundle\Controller;

use BeSimple\SoapCommon\Type\KeyValue\DateTime;
use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Mekit\Bundle\TaskBundle\Entity\Task;
use Mekit\Bundle\TaskBundle\Entity\Worklog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;


/**
 * Class WorklogController
 * @Route(path="worklog")
 */
class WorklogController extends Controller {

	/**
	 * @Route("/create", name="mekit_worklog_create")
	 * @AclAncestor("mekit_task_worklog_create")
	 * @Template("MekitTaskBundle:Worklog:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		$taskId = $this->getRequest()->get('taskId');
		$redirect = ($this->getRequest()->get('no_redirect')) ? false : true;
		$entity = $this->initWorklogItemEntity($taskId);
		$entity->setOwner($this->getUser());
		$entity->setRegistrationDate(new \DateTime());
		return $this->worklog_update($entity, $redirect);
	}



	/**
	 * @param Worklog $entity
	 * @param boolean $redirect
	 * @return array
	 */
	protected function worklog_update(Worklog $entity = null, $redirect = true) {
		$saved = false;
		if (!$entity) {
			throw new \LogicException("No entity defined!");
		}
		$taskId = $entity->getTask()->getId();

		$formAction = ($entity->getId() ?
			$this->get('router')->generate('mekit_worklog_update', ['id' => $entity->getId()]) :
			$this->get('router')->generate('mekit_worklog_create', ['taskId' => $taskId])
		);

		if ($this->get('mekit_task.form.handler.worklog.api')->process($entity)) {
			$saved = true;
		}

		return array(
			'entity' => $entity,
			'formAction' => $formAction,
			'saved' => $saved,
			'form' => $this->get('mekit_task.form.worklog.api')->createView()
		);
	}

	/**
	 * @param integer $taskId
	 * @return Worklog
	 */
	protected function initWorklogItemEntity($taskId = null) {
		if(empty($taskId)) {
			throw new \LogicException("Task defined in parameters!");
		}

		$task = $this->getDoctrine()
			->getRepository('MekitTaskBundle:Task')
			->find($taskId);

		if(!$task) {
			throw new \LogicException("There is no task with the defined id($taskId)!");
		}

		$entity = new Worklog();
		$entity->setTask($task);
		return($entity);
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getWorklogManager() {
		return $this->get('mekit_task.worklog.manager.api');
	}
}
