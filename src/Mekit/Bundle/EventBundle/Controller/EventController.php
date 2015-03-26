<?php
namespace Mekit\Bundle\EventBundle\Controller;

use Mekit\Bundle\EventBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class EventController
 */
class EventController extends Controller
{
	/**
	 * @Route("/widget/info/{id}", name="mekit_event_widget_info", requirements={"id"="\d+"})
	 * @Template(template="MekitEventBundle:Event/widget:info.html.twig")
	 * @param Event $entity
	 * @return array
	 */
	public function infoAction(Event $entity) {
		return [
			'entity' => $entity
		];
	}

	/**
	 * @Route("/widget/myActiveEvents/{widget}", name="mekit_event_dashboard_my_active_events", requirements={"widget"="[\w-]+"})
	 * @Template(template="MekitEventBundle:Event/widget:myActiveEvents.html.twig")
	 * @return array
	 */
	public function dashboardMyActiveEvents($widget) {
		$result = [];

		$result = array_merge(
			$result,
			$this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget)
		);

		return $result;
	}
}
