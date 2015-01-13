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
	 * Lists Entities related to specific ReferenceableElement
	 *
	 * @Route("/widget/relationships/{id}", name="mekit_relationship_widget_list", requirements={"id"="\d+"})
	 * @Template(template="MekitRelationshipBundle:Relationship/widget:relationships.html.twig")
	 * @param ReferenceableElement $referenceableElement
	 * @return array
	 */
	public function listRelationshipsAction(ReferenceableElement $referenceableElement) {
		$referenceManager = $this->container->get("mekit_relationship.reference_manager");
		$className = $referenceableElement->getType();
		$classConfig = $referenceManager->getRelationshipConfiguration($className);
		if(!$classConfig || $classConfig->get("referenceable") !== true) {
			throw new \InvalidArgumentException('This is not a referenceable class!');
		}
		$referenceableEntityConfigs = $referenceManager->getReferenceableEntityConfigurations();
		return [
			'referenceableElement' => $referenceableElement,
			'referenceableEntityConfigs' => $referenceableEntityConfigs
		];
	}

	/**
	 * ?
	 *
	 * @Route("/widget/related_items/{id}/{type}", name="mekit_relationship_widget_related_items", requirements={"id"="\d+"})
	 * @Template(template="MekitRelationshipBundle:Relationship/widget:relatedItems.html.twig")
	 * @param ReferenceableElement $referenceableElement
	 * @param String $type
	 * @return array
	 */
	public function listRelatedItemsAction(ReferenceableElement $referenceableElement, $type) {
		$referenceManager = $this->container->get("mekit_relationship.reference_manager");
		$className = $referenceableElement->getType();
		$classConfig = $referenceManager->getRelationshipConfiguration($className);
		if(!$classConfig || $classConfig->get("referenceable") !== true) {
			throw new \InvalidArgumentException('This is not a referenceable class!');
		}
		$referenceableEntityConfig = $referenceManager->getRelationshipConfiguration($type);
		return [
			'referenceableElement' => $referenceableElement,
			'referenceableEntityConfig' => $referenceableEntityConfig
		];
	}


}
