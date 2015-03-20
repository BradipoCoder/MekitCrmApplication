<?php
namespace Mekit\Bundle\OpportunityBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Mekit\Bundle\OpportunityBundle\Entity\Opportunity;


class OpportunityController extends Controller
{
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_opportunity_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_opportunity_view")
	 * @return array
	 */
    public function indexAction()
    {
	    return array(
		    'entity_class' => $this->container->getParameter('mekit_opportunity.opportunity.entity.class')
	    );
    }

	/**
	 * @Route("/view/{id}", name="mekit_opportunity_view", requirements={"id"="\d+"})
	 * @Template
	 * @Acl(
	 *      id="mekit_opportunity_view",
	 *      type="entity",
	 *      class="MekitOpportunityBundle:Opportunity",
	 *      permission="VIEW"
	 * )
	 * @param Opportunity $opportunity
	 * @return array
	 */
	public function viewAction(Opportunity $opportunity) {
		return [
			'entity' => $opportunity
		];
	}

	/**
	 * @Route("/create", name="mekit_opportunity_create")
	 * @Acl(
	 *      id="mekit_opportunity_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitOpportunityBundle:Opportunity"
	 * )
	 * @Template("MekitOpportunityBundle:Opportunity:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_opportunity_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_opportunity_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitOpportunityBundle:Opportunity"
	 * )
	 * @Template()
	 * @param Opportunity $opportunity
	 * @return array
	 */
	public function updateAction(Opportunity $opportunity) {
		return $this->update($opportunity);
	}

	/**
	 * @param Opportunity $entity
	 * @return array
	 */
	protected function update(Opportunity $entity = null) {
		if (!$entity) {
			/** @var Opportunity $entity */
			$entity = $this->getManager()->createEntity();

			//assign to current user
			//$entity->addUser($this->getUser());
		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_opportunity.form.opportunity'),
			function (Opportunity $entity) {
				return array(
					'route' => 'mekit_opportunity_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Opportunity $entity) {
				return array(
					'route' => 'mekit_opportunity_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.opportunity.controller.opportunity.saved.message'),
			$this->get('mekit_opportunity.form.handler.opportunity')
		);
	}


	/**
	 * @Route("/widget/info/{id}", name="mekit_opportunity_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_opportunity_view")
	 * @Template(template="MekitOpportunityBundle:Opportunity/widget:info.html.twig")
	 * @param Opportunity $opportunity
	 * @return array
	 */
	public function infoAction(Opportunity $opportunity) {
		return [
			'entity' => $opportunity
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManager() {
		return $this->get('mekit_opportunity.opportunity.manager.api');
	}
}
