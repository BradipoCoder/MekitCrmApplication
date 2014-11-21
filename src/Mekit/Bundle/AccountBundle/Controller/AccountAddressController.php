<?php

namespace Mekit\Bundle\AccountBundle\Controller;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\AccountBundle\Entity\AccountAddress;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AccountAddressController
 */
class AccountAddressController extends Controller {

	/**
	 * @Route("/address-book/{id}", name="mekit_account_address_book", requirements={"id"="\d+"})
	 * @Template(template="MekitAccountBundle:AccountAddress/widget:addressBook.html.twig")
	 * @AclAncestor("mekit_account_view")
	 */
	public function addressBookAction(Account $account) {
		return array(
			'entity' => $account,
			'address_edit_acl_resource' => 'mekit_account_update'
		);
	}

	/**
	 * @Route(
	 *      "/{accountId}/address-create",
	 *      name="mekit_account_address_create",
	 *      requirements={"accountId"="\d+"}
	 * )
	 * @Template("MekitAccountBundle:AccountAddress:update.html.twig")
	 * @AclAncestor("mekit_account_create")
	 * @ParamConverter("account", options={"id" = "accountId"})
	 */
	public function createAction(Account $account) {
		return $this->update($account, new AccountAddress());
	}

	/**
	 * @Route(
	 *      "/{accountId}/address-update/{id}",
	 *      name="mekit_account_address_update",
	 *      requirements={"accountId"="\d+","id"="\d+"}, defaults={"id"=0}
	 * )
	 * @Template("MekitAccountBundle:AccountAddress:update.html.twig")
	 * @AclAncestor("mekit_account_update")
	 * @ParamConverter("account", options={"id" = "accountId"})
	 */
	public function updateAction(Account $account, AccountAddress $address) {
		return $this->update($account, $address);
	}

	/**
	 * @param Account        $account
	 * @param AccountAddress $address
	 * @return array
	 * @throws BadRequestHttpException
	 */
	protected function update(Account $account, AccountAddress $address) {
		$responseData = array(
			'saved' => false,
			'account' => $account
		);

		if ($this->getRequest()->getMethod() == 'GET' && !$address->getId()) {
			$address->setFirstName($account->getFirstName());
			$address->setLastName($account->getLastName());
			if (!$account->getAddresses()->count()) {
				$address->setPrimary(true);
			}
		}

		if ($address->getOwner() && $address->getOwner()->getId() != $account->getId()) {
			throw new BadRequestHttpException('Address must belong to an account');
		} elseif (!$address->getOwner()) {
			$account->addAddress($address);
		}

		// Update accounts's modification date when an address is changed
		$account->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));

		//todo: define and make it work
		/*
		if ($this->get('orocrm_contact.form.handler.contact_address')->process($address)) {
			$this->getDoctrine()->getManager()->flush();
			$responseData['entity'] = $address;
			$responseData['saved'] = true;
		}

		$responseData['form'] = $this->get('orocrm_contact.contact_address.form')->createView();
		*/
		return $responseData;
	}


}