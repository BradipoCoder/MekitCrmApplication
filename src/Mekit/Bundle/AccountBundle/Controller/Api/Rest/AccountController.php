<?php
namespace Mekit\Bundle\AccountBundle\Controller\Api\Rest;

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
 * @RouteResource("account")
 * @NamePrefix("mekit_api_")
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class AccountController extends RestController implements ClassResourceInterface {

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
	 *      description="Get all account items",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_account_view")
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
	 *      description="Get account item",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_account_view")
	 * @return Response
	 */
	public function getAction($id) {
		return $this->handleGetRequest($id);
	}

	/**
	 * REST PUT
	 *
	 * @param int $id Account item id
	 *
	 * @ApiDoc(
	 *      description="Update account",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_account_update")
	 * @return Response
	 */
	public function putAction($id) {
		return $this->handleUpdateRequest($id);
	}

	/**
	 * Create new account
	 *
	 * @ApiDoc(
	 *      description="Create new account",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_account_create")
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
	 *      description="Delete Account",
	 *      resource=true
	 * )
	 * @Acl(
	 *      id="mekit_account_account_delete",
	 *      type="entity",
	 *      permission="DELETE",
	 *      class="MekitAccountBundle:Account"
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
		return $this->get('mekit_account.account.manager.api');
	}

	/**
	 * @return FormInterface
	 */
	//todo: missing api service
	public function getForm() {
		return $this->get('mekit_account.form.account.api');
	}

	/**
	 * @return ApiFormHandler
	 */
	//todo: missing api service
	public function getFormHandler() {
		return $this->get('mekit_account.form.handler.account.api');
	}

}