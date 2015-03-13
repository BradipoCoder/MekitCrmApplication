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
}
