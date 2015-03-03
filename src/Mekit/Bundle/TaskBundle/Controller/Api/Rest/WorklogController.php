<?php
namespace Mekit\Bundle\TaskBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("worklog")
 * @NamePrefix("mekit_api_")
 */
class WorklogController extends RestController implements ClassResourceInterface
{
	/**
	 * REST GET list
	 *
	 * @QueryParam(
	 *      name="page",
	 *      requirements="\d+",
	 *      nullable=true,
	 *      description="Page number, starting from 1. Defaults to 1."
	 * )
	 * @QueryParam(
	 *      name="limit",
	 *      requirements="\d+",
	 *      nullable=true,
	 *      description="Number of items per page. defaults to 10."
	 * )
	 * @ApiDoc(
	 *      description="Get all items",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_task_worklog_view")
	 * @return Response
	 */
	public function cgetAction() {
		return $this->handleGetListRequest();
	}

	/**
	 * REST GET item
	 *
	 * @param string $id
	 *
	 * @ApiDoc(
	 *      description="Get a single item",
	 *      resource=true
	 * )
	 * @Acl(
	 *      id="mekit_task_worklog_view",
	 *      type="entity",
	 *      permission="VIEW",
	 *      class="MekitTaskBundle:Worklog"
	 * )
	 * @return Response
	 */
	public function getAction($id) {
		return $this->handleGetRequest($id);
	}

	/**
	 * REST PUT (update)
	 *
	 * @param int $id
	 *
	 * @ApiDoc(
	 *      description="Update item",
	 *      resource=true
	 * )
	 * @Acl(
	 *      id="mekit_task_worklog_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitTaskBundle:Worklog"
	 * )
	 * @return Response
	 */
	public function putAction($id) {
		return $this->handleUpdateRequest($id);
	}

	/**
	 * REST POST (create)
	 *
	 * @ApiDoc(
	 *      description="Create new item",
	 *      resource=true
	 * )
	 * @Acl(
	 *      id="mekit_task_worklog_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitTaskBundle:Worklog"
	 * )
	 */
	public function postAction() {
		return $this->handleCreateRequest();
	}

	/**
	 * REST DELETE
	 *
	 * @param int $id
	 *
	 * @ApiDoc(
	 *      description="Delete item",
	 *      resource=true
	 * )
	 * @Acl(
	 *      id="mekit_task_worklog_delete",
	 *      type="entity",
	 *      permission="DELETE",
	 *      class="MekitTaskBundle:Worklog"
	 * )
	 * @return Response
	 */
	public function deleteAction($id) {
		return $this->handleDeleteRequest($id);
	}

	/**
	 * Get entity Manager
	 *
	 * @return ApiEntityManager
	 */
	public function getManager() {
		return $this->get('mekit_task.worklog.manager.api');
	}

	/**
	 * @return FormInterface
	 */
	public function getForm() {
		return $this->get('mekit_task.form.worklog.api');
	}

	/**
	 * @return ApiFormHandler
	 */
	public function getFormHandler() {
		return $this->get('mekit_task.form.handler.worklog.api');
	}
}