<?php

namespace Mekit\Bundle\ListBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Entity\UserApi;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\ListItem;

use Oro\Bundle\OrganizationBundle\Entity\Manager\BusinessUnitManager;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * Class ListController
 */
class ListController extends Controller{

	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_list_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_list_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
			'entity_class' => $this->container->getParameter('mekit_list.listgroup.entity.class')
		);
	}

	/**
	 * @Route("/view/{id}", name="mekit_list_view", requirements={"id"="\d+"})
	 * @Template
	 * @Acl(
	 *      id="mekit_list_view",
	 *      type="entity",
	 *      class="MekitListBundle:ListGroup",
	 *      permission="VIEW"
	 * )
	 * @param ListGroup $listGroup
	 * @return array
	 */
	public function viewAction(ListGroup $listGroup) {
		return [
			'entity' => $listGroup
		];
	}


	/**
	 * @Route("/widget/info/{id}", name="mekit_list_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_list_view")
	 * @Template(template="MekitListBundle:List/widget:listGroupInfo.html.twig")
	 * @param ListGroup $listGroup
	 * @return array
	 */
	public function infoAction(ListGroup $listGroup) {
		return [
			'entity' => $listGroup
		];
	}

	/**
	 * @Route("/widget/listitems/{id}", name="mekit_list_widget_listitems", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_list_view")
	 * @Template(template="MekitListBundle:List/widget:listItems.html.twig")
	 * @param ListGroup $listGroup
	 * @return array
	 */
	public function listItemsAction(ListGroup $listGroup) {
		return [
			'entity' => $listGroup
		];
	}

}