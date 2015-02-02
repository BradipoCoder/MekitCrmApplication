<?php
namespace Mekit\Bundle\CampaignBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

/**
 * Class CampaignController
 */
class CampaignController extends Controller {

	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_campaign_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_campaign_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
//			'entity_class' => $this->container->getParameter('mekit_campaign.campaign.entity.class')
		);
	}
}
