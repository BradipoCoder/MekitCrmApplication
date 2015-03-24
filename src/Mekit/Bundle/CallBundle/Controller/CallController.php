<?php
namespace Mekit\Bundle\CallBundle\Controller;

use Mekit\Bundle\CallBundle\Entity\Call;
use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class CallController
 */
class CallController extends Controller
{
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_call_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_call_view")
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
	 * @Acl(
	 *      id="mekit_call_view",
	 *      type="entity",
	 *      permission="VIEW",
	 *      class="MekitCallBundle:Call"
	 * )
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
	 *      class="MekitCallBundle:Call"
	 * )
	 * @Template("MekitCallBundle:Call:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		$entity = $this->initCallEntity();
		$formAction = $this->get('oro_entity.routing_helper')->generateUrlByRequest(
			'mekit_call_create', $this->getRequest()
		);

		return $this->update($entity, $formAction);
	}

	/**
	 * @Route("/update/{id}", name="mekit_call_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_call_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitCallBundle:Call"
	 * )
	 * @Template()
	 * @param Call $entity
	 * @return array
	 */
	public function updateAction(Call $entity) {
		$formAction = $this->get('router')->generate('mekit_call_update', ['id' => $entity->getId()]);

		return $this->update($entity, $formAction);
	}

	/**
	 * @param Call $entity
	 * @param string $formAction
	 * @return array
	 */
	protected function update(Call $entity, $formAction) {
		$saved = false;
		$isWidget = ($this->getRequest()->get('_widgetContainer', false) != false);
		$formHandler = (!$isWidget ? $this->get('mekit_call.form.handler.call') : $this->get(
			'mekit_call.form.handler.call.api'
		));

		if ($formHandler->process($entity)) {
			if (!$isWidget) {
				$this->get('session')->getFlashBag()->add(
					'success', $this->get('translator')->trans('mekit.call.controller.saved.message')
				);

				return $this->get('oro_ui.router')->redirectAfterSave(
					array(
						'route' => 'mekit_call_update',
						'parameters' => array('id' => $entity->getId())
					), array(
						'route' => 'mekit_call_view',
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
	 * @return Call
	 */
	protected function initCallEntity() {
		/** @var Event $event */
		$event = $this->getEventManager()->createEntity();
		$event->setStartDate(new \DateTime());

		/** @var Call $entity */
		$entity = $this->getCallManager()->createEntity();
		$entity->setOwner($this->getUser());
		$entity->addUser($this->getUser());
		$entity->setDirection('out');

		//set relationship between Call and Event
		$entity->setEvent($event);
		$event->setCall($entity);

		return ($entity);
	}

	/**
	 * @Route("/widget/info/{id}", name="mekit_call_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_call_view")
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
	protected function getCallManager() {
		return $this->get('mekit_call.call.manager.api');
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getEventManager() {
		return $this->get('mekit_event.event.manager.api');
	}
}
