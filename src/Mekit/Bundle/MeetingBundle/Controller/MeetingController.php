<?php
namespace Mekit\Bundle\MeetingBundle\Controller;

use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class MeetingController
 */
class MeetingController extends Controller
{
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_meeting_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_meeting_view")
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
	 * @Acl(
	 *      id="mekit_meeting_view",
	 *      type="entity",
	 *      permission="VIEW",
	 *      class="MekitMeetingBundle:Meeting"
	 * )
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
	 *      class="MekitMeetingBundle:Meeting"
	 * )
	 * @Template("MekitMeetingBundle:Meeting:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		$entity = $this->initMeetingEntity();
		$formAction = $this->get('oro_entity.routing_helper')->generateUrlByRequest(
			'mekit_meeting_create', $this->getRequest()
		);

		return $this->update($entity, $formAction);
	}

	/**
	 * @Route("/update/{id}", name="mekit_meeting_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_meeting_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitMeetingBundle:Meeting"
	 * )
	 * @Template()
	 * @param Meeting $entity
	 * @return array
	 */
	public function updateAction(Meeting $entity) {
		$formAction = $this->get('router')->generate('mekit_meeting_update', ['id' => $entity->getId()]);

		return $this->update($entity, $formAction);
	}

	/**
	 * @param Meeting $entity
	 * @param string $formAction
	 * @return array
	 */
	protected function update(Meeting $entity, $formAction) {
		$saved = false;
		$isWidget = ($this->getRequest()->get('_widgetContainer', false) != false);
		$formHandler = (!$isWidget ? $this->get('mekit_meeting.form.handler.meeting') : $this->get(
			'mekit_meeting.form.handler.meeting.api'
		));

		if ($formHandler->process($entity)) {
			if (!$isWidget) {
				$this->get('session')->getFlashBag()->add(
					'success', $this->get('translator')->trans('mekit.meeting.controller.saved.message')
				);

				return $this->get('oro_ui.router')->redirectAfterSave(
					array(
						'route' => 'mekit_meeting_update',
						'parameters' => array('id' => $entity->getId())
					), array(
						'route' => 'mekit_meeting_view',
						'parameters' => array('id' => $entity->getId())
					)
				);
			}
			$saved = true;
		}

		return array(
			'entity' => $entity,
			'saved' => $saved,
			'form' => $formHandler->getForm()->createView(),
			'formAction' => $formAction,
		);
	}

	/**
	 * @return Meeting
	 */
	protected function initMeetingEntity() {
		/** @var Event $event */
		$event = $this->getEventManager()->createEntity();
		$event->setStartDate(new \DateTime());

		/** @var Meeting $entity */
		$entity = $this->getMeetingManager()->createEntity();
		$entity->setOwner($this->getUser());
		$entity->addUser($this->getUser());

		//set relationship between Meeting and Event
		$entity->setEvent($event);
		$event->setMeeting($entity);

		return ($entity);
	}

	/**
	 * @Route(
	 *      "/widget/info/{id}",
	 *      name="mekit_meeting_widget_info",
	 *      requirements={"id"="\d+"},
	 *      options={"expose"="true"}
	 * )
	 * @AclAncestor("mekit_meeting_view")
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
	protected function getMeetingManager() {
		return $this->get('mekit_meeting.meeting.manager.api');
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getEventManager() {
		return $this->get('mekit_event.event.manager.api');
	}
}
