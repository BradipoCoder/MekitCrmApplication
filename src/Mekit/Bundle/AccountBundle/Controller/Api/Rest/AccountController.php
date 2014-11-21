<?php
namespace Mekit\Bundle\AccountBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use OroCRM\Bundle\ContactBundle\Entity\Contact;
use OroCRM\Bundle\ContactBundle\Entity\ContactAddress;
use OroCRM\Bundle\ContactBundle\Form\Type\ContactApiType;

/**
 * @RouteResource("account")
 * @NamePrefix("mekit_api_")
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class AccountController extends RestController implements ClassResourceInterface {

	/**
	 * REST GET item
	 *
	 * @param string $id
	 *
	 * @ApiDoc(
	 *      description="Get account item",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_view")
	 * @return Response
	 */
	public function getAction($id) {
		return $this->handleGetRequest($id);
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
	public function getForm()
	{
		return $this->get('mekit_account.form.account');
	}

	/**
	 * @return ApiFormHandler
	 */
	//todo: missing api service
	public function getFormHandler()
	{
		return $this->get('mekit_account.form.handler.account');
	}

}