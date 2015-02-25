<?php

namespace Mekit\Bundle\EventBundle\Controller;

use Mekit\Bundle\EventBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;


/**
 * Class EventController
 */
class EventController extends Controller {
	/**
	 * @Route("/widget/info/{id}", name="mekit_event_widget_info", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_event_view",
	 *      type="entity",
	 *      class="MekitEventBundle:Event",
	 *      permission="VIEW"
	 * )
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
