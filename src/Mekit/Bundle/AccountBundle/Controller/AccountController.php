<?php

namespace Mekit\Bundle\AccountBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Entity\UserApi;
use Mekit\Bundle\AccountBundle\Entity\Account;

use Oro\Bundle\OrganizationBundle\Entity\Manager\BusinessUnitManager;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * Class AccountController
 */
class AccountController extends Controller {

	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_account_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_account_account_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
			'entity_class' => $this->container->getParameter('mekit_account.account.entity.class')
		);
	}

	/**
	 * @Route("/view/{id}", name="mekit_account_view", requirements={"id"="\d+"})
	 * @Template
	 * @Acl(
	 *      id="mekit_account_account_view",
	 *      type="entity",
	 *      class="MekitAccountBundle:Account",
	 *      permission="VIEW"
	 * )
	 * @param Account $account
	 * @return array
	 */
	public function viewAction(Account $account) {
		return [
			'entity' => $account
		];
	}

	/**
	 * @Route("/create", name="mekit_account_create")
	 * @Acl(
	 *      id="mekit_account_account_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitAccountBundle:Account"
	 * )
	 * @Template("MekitAccountBundle:Account:update.html.twig")
	 * @return array
	 */
	public function createAction()
	{
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_account_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_account_account_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitAccountBundle:Account"
	 * )
	 * @Template()
	 * @param Account $account
	 * @return array
	 */
	public function updateAction(Account $account)
	{
		return $this->update($account);
	}

	/**
	 * @Route("/widget/info/{id}", name="mekit_account_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_account_account_view")
	 * @Template(template="MekitAccountBundle:Account/widget:info.html.twig")
	 * @param Account $account
	 * @return array
	 */
	public function infoAction(Account $account) {
		return [
			'account' => $account
		];
	}



	/**
	 * @param Account $entity
	 * @return array
	 */
	protected function update(Account $entity = null)
	{
		if (!$entity) {
			$entity = $this->getManager()->createEntity();
		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_account.form.account'),
			function (Account $entity) {
				return array(
					'route' => 'mekit_account_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Account $entity) {
				return array(
					'route' => 'mekit_account_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.account.controller.account.saved.message'),
			$this->get('mekit_account.form.handler.account')
		);
	}


	/**
	 * @return ApiEntityManager
	 */
	protected function getManager()
	{
		return $this->get('mekit_account.account.manager.api');
	}
}
