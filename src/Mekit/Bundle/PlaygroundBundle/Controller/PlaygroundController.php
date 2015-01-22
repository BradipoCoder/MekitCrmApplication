<?php
namespace Mekit\Bundle\PlaygroundBundle\Controller;

use Doctrine\ORM\EntityManager;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;

/**
 * Class DefaultController
 */
class PlaygroundController extends Controller {
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_playground_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @return array
	 */
	public function indexAction() {
		$data = [
			'dumpdata' => 'Nothing here'
		];
		return $data;
	}


	/**
	 * @Route("/update/{id}", name="mekit_playground_update", requirements={"id"="\d+"})
	 * @Template(template="MekitPlaygroundBundle:Playground:accountUpdate.html.twig")
	 * @param Account $account
	 * @return array
	 */
	public function updateAction(Account $account) {
		return $this->update($account);
	}

	/**
	 * @param Account $entity
	 * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	protected function update(Account $entity = null) {
		if (!$entity) {
			throw new \Exception("ONLY EDIT PLS!");
		}
		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_playground.form.account'),
			function (Account $entity) {
				return array(
					'route' => 'mekit_playground_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Account $entity) {
				return array(
					'route' => 'mekit_playground_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.account.controller.account.saved.message')
		);
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getAccountManager() {
		return $this->get('mekit_account.account.manager.api');
	}

}
