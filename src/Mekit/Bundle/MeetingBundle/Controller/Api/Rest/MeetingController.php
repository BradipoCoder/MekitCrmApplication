<?php
namespace Mekit\Bundle\MeetingBundle\Controller\Api\Rest;

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
 * @RouteResource("meeting")
 * @NamePrefix("mekit_api_")
 */
class MeetingController extends RestController implements ClassResourceInterface {
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
	 * @AclAncestor("mekit_meeting_view")
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
	 * @AclAncestor("mekit_meeting_view")
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
	 * @AclAncestor("mekit_meeting_update")
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
	 * @AclAncestor("mekit_meeting_create")
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
	 *      id="mekit_meeting_delete",
	 *      type="entity",
	 *      permission="DELETE",
	 *      class="MekitMeetingBundle:Meeting"
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
		return $this->get('mekit_meeting.meeting.manager.api');
	}

	/**
	 * @return FormInterface
	 */
	public function getForm() {
		return $this->get('mekit_meeting.form.meeting.api');
	}

	/**
	 * @return ApiFormHandler
	 */
	public function getFormHandler() {
		return $this->get('mekit_meeting.form.handler.meeting.api');
	}
}