<?php
namespace Mekit\Bundle\CrmBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CrmController
 */
class CrmController extends Controller {

	/**
	 * Shows requested datagrid with elements related to entity identified by $id
	 *
	 * @Route("/widget/related/{datagrid_name}/{id}", name="mekit_crm_widget_related", requirements={"id"="\d+"})
	 * @Template(template="MekitCrmBundle:Crm/widget:related.html.twig")
	 * @param String $datagrid_name - The name of the datagrid to show
	 * @param Integer $id - The id of the entity for which the datagrid will list related entities
	 * @return array
	 */
	public function relatedContacts($datagrid_name, $id) {
		return[
			'datagrid_name' => $datagrid_name,
			'id' => $id
		];
	}

}