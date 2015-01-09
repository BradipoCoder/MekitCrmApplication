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
	 * @Route("/widget/related_contacts/{id}/{datagrid_name}", name="mekit_relationship_widget_list", requirements={"id"="\d+"})
	 * @Template(template="MekitRelationshipBundle:Relationship/widget:relatedElements.html.twig")
	 * @param ReferenceableElement $referenceableElement
	 * @param String $datagrid_name
	 * @return array
	 */
	public function listReferencedItemsAction(ReferenceableElement $referenceableElement, $datagrid_name) {
		return [
			'referenceableElement' => $referenceableElement,
			'datagridName' => $datagrid_name
		];
	}
}
