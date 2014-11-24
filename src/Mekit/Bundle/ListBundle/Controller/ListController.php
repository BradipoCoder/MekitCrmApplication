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
 * todo: this controller should be split into two
 */
class ListController extends Controller {

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
	 * @Route("/create", name="mekit_list_create")
	 * @Acl(
	 *      id="mekit_list_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitListBundle:ListGroup"
	 * )
	 * @Template("MekitListBundle:List:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_list_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_list_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitListBundle:ListGroup"
	 * )
	 * @Template()
	 * @param ListGroup $listGroup
	 * @return array
	 */
	public function updateAction(ListGroup $listGroup) {
		return $this->update($listGroup);
	}

	/**
	 * @param ListGroup $entity
	 * @return array
	 */
	protected function update(ListGroup $entity = null) {
		if (!$entity) {
			$entity = $this->getListGroupManager()->createEntity();
		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_list.form.listgroup'),
			function (ListGroup $entity) {
				return array(
					'route' => 'mekit_list_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (ListGroup $entity) {
				return array(
					'route' => 'mekit_list_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.list.controller.list.saved.message'),
			$this->get('mekit_list.form.handler.listgroup')
		);
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




	/**
	 * @Route("/item/create", name="mekit_listitem_create")
	 * @Acl(
	 *      id="mekit_listitem_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitListBundle:ListItem"
	 * )
	 * @Template("MekitListBundle:Item:update.html.twig")
	 * @return array
	 */
	public function listItemCreateAction() {
		$listGroupId = $this->getRequest()->get('listGroupId');
		//die("LGID: " . $listGroupId);
		$redirect = ($this->getRequest()->get('no_redirect')) ? false : true;
		$entity = $this->initListItemEntity($listGroupId);
		//die("LGID: " . $listGroupId);
		return $this->listitem_update($entity, $redirect);
	}

	/**
	 * @param integer $listGroupId
	 * @return ListItem
	 */
	protected function initListItemEntity($listGroupId = null) {
		if(!$listGroupId) {
			throw new \LogicException("No list group id defined in parameters!");
		}
		$listGroup = $this->getDoctrine()
			->getRepository('MekitListBundle:ListGroup')
			->find($listGroupId);
		if(!$listGroup) {
			throw new \LogicException("There is no list group with the defined id($listGroupId)!");
		}
		$entity = new ListItem();
		$entity->setListGroup($listGroup);
		return($entity);
	}

	/**
	 * @Route("/item/update/{id}", name="mekit_listitem_update", requirements={"id"="\w+"})
	 *  @Acl(
	 *      id="mekit_listitem_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitListBundle:ListItem"
	 * )
	 * @Template("MekitListBundle:Item:update.html.twig")
	 * @param ListItem $listItem
	 * @return array
	 */
	public function listItemUpdateAction(ListItem $listItem) {
		return $this->listitem_update($listItem);
	}

	/**
	 * @param ListItem $entity
	 * @param boolean $redirect
	 * @return array
	 */
	protected function listitem_update(ListItem $entity = null, $redirect = true) {
		$saved = false;
		if (!$entity) {
			throw new \LogicException("No entity defined!");
		}

		$listGroupId = $entity->getListGroup()->getId();
		$formAction = ($entity->getId() ?
			$this->get('router')->generate('mekit_listitem_update', ['id' => $entity->getId()]) :
			$this->get('router')->generate('mekit_listitem_create', ['listGroupId' => $listGroupId])
		);


		if ($this->get('mekit_list.form.handler.listitem')->process($entity)) {
			$saved = true;
		}


		return array(
			'entity' => $entity,
			'formAction' => $formAction,
			'saved' => $saved,
			'form' => $this->get('mekit_list.form.listitem')->createView()
		);
	}






	/**
	 * @return ApiEntityManager - ListGroup
	 */
	protected function getListGroupManager() {
		return $this->get('mekit_list.listgroup.manager.api');
	}

	/**
	 * @return ApiEntityManager - ListItem
	 */
	protected function getListItemManager() {
		return $this->get('mekit_list.listitem.manager.api');
	}
}