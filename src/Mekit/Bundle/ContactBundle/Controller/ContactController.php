<?php
namespace Mekit\Bundle\ContactBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ListBundle\Entity\ListItem;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;

/**
 * Class ContactController
 */
class ContactController extends Controller {
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_contact_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_contact_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
			'entity_class' => $this->container->getParameter('mekit_contact.contact.entity.class')
		);
	}

	/**
	 * @Route("/view/{id}", name="mekit_contact_view", requirements={"id"="\d+"})
	 * @Template
	 * @Acl(
	 *      id="mekit_contact_view",
	 *      type="entity",
	 *      class="MekitContactBundle:Contact",
	 *      permission="VIEW"
	 * )
	 * @param Contact $contact
	 * @return array
	 */
	public function viewAction(Contact $contact) {
		return [
			'entity' => $contact
		];
	}



}