<?php

namespace Mekit\Bundle\AccountBundle\Controller;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

/**
 * Class AccountAddressController
 */
class AccountAddressController extends Controller{

	/**
	 * @Route("/address-book/{id}", name="mekit_account_address_book", requirements={"id"="\d+"})
	 * @Template(template="MekitAccountBundle:AccountAddress/widget:addressBook.html.twig")
	 * @AclAncestor("mekit_account_view")
	 */
	public function addressBookAction(Account $account)
	{
		return array(
			'entity' => $account,
			'address_edit_acl_resource' => 'mekit_xxx'
		);
	}

}