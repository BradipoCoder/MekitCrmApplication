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
 * Class ListItemController
 * @Route("/item")
 */
class ListItemController extends Controller
{

	/**
	 * @Route("/create", name="mekit_listitem_create")
	 * @Acl(
	 *      id="mekit_listitem_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitListBundle:ListItem"
	 * )
	 * @Template("MekitListBundle:ListItem:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		$listGroupId = $this->getRequest()->get('listGroupId');
		$entity = $this->initListItemEntity($listGroupId);
		return $this->listitem_update($entity);
	}

	/**
	 * @Route("/update/{id}", name="mekit_listitem_update", requirements={"id"="\w+"})
	 * @Acl(
	 *      id="mekit_listitem_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitListBundle:ListItem"
	 * )
	 * @Template
	 * @param ListItem $listItem
	 * @return array
	 */
	public function updateAction(ListItem $listItem) {
		return $this->listitem_update($listItem);
	}

	/**
	 * @param integer $listGroupId
	 * @return ListItem
	 */
	protected function initListItemEntity($listGroupId = null) {
		if (empty($listGroupId)) {
			throw new \LogicException("No list group id defined in parameters!");
		}
		$listGroup = $this->getDoctrine()->getRepository('MekitListBundle:ListGroup')->find($listGroupId);
		if (!$listGroup) {
			throw new \LogicException("There is no list group with the defined id($listGroupId)!");
		}
		$entity = new ListItem();
		$entity->setListGroup($listGroup);
		return ($entity);
	}

	/**
	 * @param ListItem $entity
	 * @return array
	 */
	protected function listitem_update(ListItem $entity = null) {
		$saved = false;
		if (!$entity) {
			throw new \LogicException("No entity defined!");
		}

		$listGroupId = $entity->getListGroup()->getId();
		$formAction = ($entity->getId() ? $this->get('router')->generate(
			'mekit_listitem_update', ['id' => $entity->getId()]
		) : $this->get('router')->generate('mekit_listitem_create', ['listGroupId' => $listGroupId]));


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
	 * @return ApiEntityManager
	 */
	protected function getListItemManager() {
		return $this->get('mekit_list.listitem.manager.api');
	}
}