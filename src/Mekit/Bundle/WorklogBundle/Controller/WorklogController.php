<?php
namespace Mekit\Bundle\WorklogBundle\Controller;

use Mekit\Bundle\TaskBundle\Entity\Task;
use Mekit\Bundle\WorklogBundle\Entity\Worklog;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class WorklogController
 */
class WorklogController extends Controller
{
	/**
	 * @Route("/create", name="mekit_worklog_create")
	 * @Acl(
	 *      id="mekit_worklog_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitWorklogBundle:Worklog"
	 * )
	 * @Template("MekitWorklogBundle:Worklog:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		$taskId = $this->getRequest()->get('taskId');
		$entity = $this->initWorklogEntity($taskId);
		$entity->setOwner($this->getUser());
		$entity->setExecutionDate(new \DateTime());

		return $this->worklog_update($entity);
	}

	/**
	 * @Route("/update/{id}", name="mekit_worklog_update", requirements={"id"="\w+"})
	 * @Acl(
	 *      id="mekit_worklog_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitWorklogBundle:Worklog"
	 * )
	 * @Template("MekitWorklogBundle:Worklog:update.html.twig")
	 * @param Worklog $worklog
	 * @return array
	 */
	public function updateAction(Worklog $worklog) {
		return $this->worklog_update($worklog);
	}

	/**
	 * @param Worklog $entity
	 * @return array
	 */
	protected function worklog_update(Worklog $entity = null) {
		$saved = false;
		if (!$entity) {
			throw new \LogicException("No entity defined!");
		}
		$taskId = $entity->getTask()->getId();

		$formAction = ($entity->getId() ? $this->get('router')->generate(
			'mekit_worklog_update',
			['id' => $entity->getId()]
		) : $this->get('router')->generate(
			'mekit_worklog_create',
			['taskId' => $taskId]
		));

		if ($this->get('mekit_worklog.form.handler.worklog.api')->process($entity)) {
			$saved = true;
		}

		return array(
			'entity' => $entity,
			'formAction' => $formAction,
			'saved' => $saved,
			'form' => $this->get('mekit_worklog.form.worklog.api')->createView()
		);
	}

	/**
	 * @param integer $taskId
	 * @return Worklog
	 */
	protected function initWorklogEntity($taskId = null) {
		if (empty($taskId)) {
			throw new \LogicException("Task defined in parameters!");
		}

		$task = $this->getDoctrine()->getRepository('MekitTaskBundle:Task')->find($taskId);

		if (!$task) {
			throw new \LogicException("There is no task with the defined id($taskId)!");
		}

		$entity = new Worklog();
		$entity->setTask($task);

		return ($entity);
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getWorklogManager() {
		return $this->get('mekit_worklog.worklog.manager.api');
	}
}
