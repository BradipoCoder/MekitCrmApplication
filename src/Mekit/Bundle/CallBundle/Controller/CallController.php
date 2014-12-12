<?php
namespace Mekit\Bundle\CallBundle\Controller;

use BeSimple\SoapCommon\Type\KeyValue\DateTime;
use Mekit\Bundle\CallBundle\Entity\Call;
use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;


/**
 * Class CallController
 */
class CallController extends Controller {
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_call_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_event_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
			'entity_class' => $this->container->getParameter('mekit_call.call.entity.class')
		);
	}

	/**
	 * @Route("/view/{id}", name="mekit_call_view", requirements={"id"="\d+"})
	 * @Template
	 * @AclAncestor("mekit_event_view")
	 * @param Call $entity
	 * @return array
	 */
	public function viewAction(Call $entity) {
		return [
			'entity' => $entity
		];
	}

	/**
	 * @Route("/create", name="mekit_call_create")
	 * @Acl(
	 *      id="mekit_call_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitEventBundle:Event"
	 * )
	 * @Template("MekitCallBundle:Call:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_call_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_call_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitEventBundle:Event"
	 * )
	 * @Template()
	 * @param Call $entity
	 * @return array
	 */
	public function updateAction(Call $entity) {
		return $this->update($entity);
	}

	/**
	 * @param Call $entity
	 * @return array
	 */
	protected function update(Call $entity = null) {
		if (!$entity) {
			/** @var ListItemRepository $listItemRepo */
			$listItemRepo = $this->getDoctrine()->getRepository('MekitListBundle:ListItem');

			/** @var Event $event */
			$event = $this->getManagerEvent()->createEntity();
			$event->setStartDate(new \DateTime());
			$event->setState($listItemRepo->getDefaultItemForGroup("EVENT_STATE"));
			$event->setPriority($listItemRepo->getDefaultItemForGroup("EVENT_PRIORITY"));
			$event->setOwner($this->getUser());

			/** @var Call $entity */
			$entity = $this->getManagerCall()->createEntity();


			//set relationship between entity and Event
			$entity->setEvent($event);
			$event->setCall($entity);
		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_call.form.call'),
			function (Call $entity) {
				return array(
					'route' => 'mekit_call_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Call $entity) {
				return array(
					'route' => 'mekit_call_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.call.controller.saved.message'),
			$this->get('mekit_call.form.handler.call')
		);
	}



	/**
	 * @Route("/widget/info/{id}", name="mekit_call_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_event_view")
	 * @Template(template="MekitCallBundle:Call/widget:info.html.twig")
	 * @param Call $entity
	 * @return array
	 */
	public function infoAction(Call $entity) {
		return [
			'entity' => $entity
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManagerCall() {
		return $this->get('mekit_call.call.manager.api');
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManagerEvent() {
		return $this->get('mekit_event.event.manager.api');
	}
}
