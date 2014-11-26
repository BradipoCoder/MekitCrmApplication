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

	/**
	 * @Route("/create", name="mekit_contact_create")
	 * @Acl(
	 *      id="mekit_contact_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitContactBundle:Contact"
	 * )
	 * @Template("MekitContactBundle:Contact:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_contact_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_contact_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitContactBundle:Contact"
	 * )
	 * @Template()
	 * @param Contact $contact
	 * @return array
	 */
	public function updateAction(Contact $contact) {
		return $this->update($contact);
	}

	/**
	 * @param Contact $entity
	 * @return array
	 */
	protected function update(Contact $entity = null) {
		if (!$entity) {
			/** @var Contact $entity */
			$entity = $this->getManager()->createEntity();

			//assign to current user
			$entity->setAssignedTo($this->getUser());

			/** @var ListItemRepository $listItemRepo */
//			$listItemRepo = $this->getDoctrine()->getRepository('MekitListBundle:ListItem');

			//set defaults for list items
//			$entity->setType($listItemRepo->getDefaultItemForGroup("ACCOUNT_TYPE"));
//			$entity->setState($listItemRepo->getDefaultItemForGroup("ACCOUNT_STATE"));
//			$entity->setIndustry($listItemRepo->getDefaultItemForGroup("ACCOUNT_INDUSTRY"));
//			$entity->setSource($listItemRepo->getDefaultItemForGroup("ACCOUNT_SOURCE"));

		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_contact.form.contact'),
			function (Contact $entity) {
				return array(
					'route' => 'mekit_contact_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Contact $entity) {
				return array(
					'route' => 'mekit_contact_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.contact.controller.contact.saved.message'),
			$this->get('mekit_contact.form.handler.contact')
		);
	}


	/**
	 * @Route("/widget/info/{id}", name="mekit_contact_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_contact_view")
	 * @Template(template="MekitContactBundle:Contact/widget:info.html.twig")
	 * @param Contact $contact
	 * @return array
	 */
	public function infoAction(Contact $contact) {
		return [
			'entity' => $contact
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManager() {
		return $this->get('mekit_contact.contact.manager.api');
	}
}