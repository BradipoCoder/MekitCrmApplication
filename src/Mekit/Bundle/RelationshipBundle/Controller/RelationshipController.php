<?php
namespace Mekit\Bundle\RelationshipBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;


/**
 * Class RelationshipController
 */
class RelationshipController extends Controller {
	/**
	 * Show datagrid of related items (of given type) referenced by a specific ReferenceableElement
	 *
	 * @Route("/widget/related_items/{id}/{type}", name="mekit_relationship_widget_related_items", requirements={"id"="\d+"})
	 * @Template(template="MekitRelationshipBundle:Relationship/widget:relatedItems.html.twig")
	 * @param ReferenceableElement $referenceableElement
	 * @param String $type
	 * @return array
	 */
	public function listRelatedItemsAction(ReferenceableElement $referenceableElement, $type) {
		$referenceManager = $this->container->get("mekit_relationship.reference_manager");
		$className = $referenceManager->getRealClassName($referenceableElement->getBaseEntity());
		$classConfig = $referenceManager->getRelationshipConfiguration($className);
		if(!$classConfig || $classConfig->get("referenceable") !== true) {
			throw new \InvalidArgumentException('This is not a referenceable class('.$className.')!');
		}
		$referenceableEntityConfig = $referenceManager->getRelationshipConfiguration($type);
		return [
			'referenceableElement' => $referenceableElement,
			'referenceableEntityConfig' => $referenceableEntityConfig,
			'entity_class' => $type
		];
	}

	/**
	 * Show datagrid of "un-related" items (of given type) for reference selection
	 * "id" is passed so we can exclude items already selected
	 *
	 * @Route("/widget/select_items/{id}/{type}", name="mekit_relationship_widget_select_items", requirements={"id"="\d+"})
	 * @Template(template="MekitRelationshipBundle:Relationship/widget:selectItems.html.twig")
	 * @param ReferenceableElement $referenceableElement
	 * @param String $type
	 * @return array
	 */
	public function selectRelatedItemsAction(ReferenceableElement $referenceableElement, $type) {
		$referenceManager = $this->container->get("mekit_relationship.reference_manager");
		$className = $referenceManager->getRealClassName($referenceableElement->getBaseEntity());
		$classConfig = $referenceManager->getRelationshipConfiguration($className);
		if(!$classConfig || $classConfig->get("referenceable") !== true) {
			throw new \InvalidArgumentException('This is not a referenceable class('.$className.')!');
		}
		$referenceableEntityConfig = $referenceManager->getRelationshipConfiguration($type);
		//
		$response = [];
		$saved = false;
		if ($this->container->get('mekit_relationship.form.handler.relationship_assignment')->process($referenceableElement)) {
			$saved = true;
		}
		//
		$response = array_merge($response, [
			'saved' => $saved,
			'referenceableElement' => $referenceableElement,
			'referenceableEntityConfig' => $referenceableEntityConfig,
			'entity_class' => $type,
		]);

		if (!$saved) {
			$response = array_merge($response, [
				'form' => $this->get('mekit_relationship.form.relationship_assignment')->createView()
			]);
		}
		return $response;
	}
}
