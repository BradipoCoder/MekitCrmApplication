<?php
namespace Mekit\Bundle\MeetingBundle\Controller;

use BeSimple\SoapCommon\Type\KeyValue\DateTime;
use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;


/**
 * Class MeetingController
 */
class MeetingController extends Controller {
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_meeting_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_event_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
			'entity_class' => $this->container->getParameter('mekit_meeting.meeting.entity.class')
		);
	}

	/**
	 * @Route("/view/{id}", name="mekit_meeting_view", requirements={"id"="\d+"})
	 * @Template
	 * @AclAncestor("mekit_event_view")
	 * @param Meeting $entity
	 * @return array
	 */
	public function viewAction(Meeting $entity) {
		return [
			'entity' => $entity
		];
	}

	/**
	 * @Route("/create", name="mekit_meeting_create")
	 * @Acl(
	 *      id="mekit_meeting_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitEventBundle:Event"
	 * )
	 * @Template("MekitMeetingBundle:Meeting:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_meeting_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_meeting_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitEventBundle:Event"
	 * )
	 * @Template()
	 * @param Meeting $entity
	 * @return array
	 */
	public function updateAction(Meeting $entity) {
		return $this->update($entity);
	}

	/**
	 * @param Meeting $entity
	 * @return array
	 */
	protected function update(Meeting $entity = null) {
		if (!$entity) {
			/** @var ListItemRepository $listItemRepo */
			$listItemRepo = $this->getDoctrine()->getRepository('MekitListBundle:ListItem');

			/** @var Event $event */
			$event = $this->getManagerEvent()->createEntity();
			$event->setStartDate(new \DateTime());
			$event->setState($listItemRepo->getDefaultItemForGroup("EVENT_STATE"));
			$event->setPriority($listItemRepo->getDefaultItemForGroup("EVENT_PRIORITY"));
			$event->setOwner($this->getUser());

			/** @var Meeting $entity */
			$entity = $this->getManagerMeeting()->createEntity();


			//set relationship between entity and Event
			$entity->setEvent($event);
			$event->setMeeting($entity);
		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_meeting.form.meeting'),
			function (Meeting $entity) {
				return array(
					'route' => 'mekit_meeting_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Meeting $entity) {
				return array(
					'route' => 'mekit_meeting_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.meeting.controller.saved.message'),
			$this->get('mekit_meeting.form.handler.meeting')
		);
	}



	/**
	 * @Route("/widget/info/{id}", name="mekit_meeting_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_event_view")
	 * @Template(template="MekitMeetingBundle:Meeting/widget:info.html.twig")
	 * @param Meeting $entity
	 * @return array
	 */
	public function infoAction(Meeting $entity) {
		return [
			'entity' => $entity
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManagerMeeting() {
		return $this->get('mekit_meeting.meeting.manager.api');
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManagerEvent() {
		return $this->get('mekit_event.event.manager.api');
	}
}
