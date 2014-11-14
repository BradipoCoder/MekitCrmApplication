<?php

namespace Mekit\Bundle\ListBundle\Controller\Api\Rest;

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
 * @RouteResource("listitem")
 * @NamePrefix("mekit_api_")
 */
class ListItemController extends RestController implements ClassResourceInterface {

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
	 *      description="Get all list items",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_list_view")
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
	 *      description="Get list item",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_list_view")
	 * @return Response
	 */
	public function getAction($id) {
		return $this->handleGetRequest($id);
	}

	/**
	 * REST PUT - UPDATE
	 *
	 * @param int $id list item id
	 *
	 * @ApiDoc(
	 *      description="Update list item",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_listitem_update")
	 * @return Response
	 */
	public function putAction($id) {
		return $this->handleUpdateRequest($id);
	}

	/**
	 * Create new list item
	 *
	 * @ApiDoc(
	 *      description="Create new list item",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_listitem_create")
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
	 *      description="Delete list item",
	 *      resource=true
	 * )
	 * @Acl(
	 *      id="mekit_listitem_delete",
	 *      type="entity",
	 *      permission="DELETE",
	 *      class="MekitListBundle:ListItem"
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
		return $this->get('mekit_list.listitem.manager.api');
	}

	/**
	 * @return FormInterface
	 */
	public function getForm() {
		return $this->get('mekit_list.form.listitem');
	}

	/**
	 * @return ApiFormHandler
	 */
	public function getFormHandler() {
		return $this->get('mekit_list.form.handler.listitem');
	}
}