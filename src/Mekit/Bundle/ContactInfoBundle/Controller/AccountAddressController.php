<?php

namespace Mekit\Bundle\ContactInfoBundle\Controller;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;
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
	 * @Route("/account-address-book/{id}", name="mekit_account_address_book", requirements={"id"="\d+"})
	 * @Template(template="MekitContactInfoBundle:AccountAddress/widget:addressBook.html.twig")
	 * @AclAncestor("mekit_account_account_view")
	 */
	public function addressBookAction(Account $account) {
		return array(
			'entity' => $account,
			'address_edit_acl_resource' => 'mekit_account_account_update'
		);
	}

	/**
	 * @Route(
	 *      "/{accountId}/account-address-create",
	 *      name="mekit_account_address_create",
	 *      requirements={"accountId"="\d+"}
	 * )
	 * @Template(template="MekitContactInfoBundle:AccountAddress:update.html.twig")
	 * @AclAncestor("mekit_account_account_create")
	 * @ParamConverter("account", options={"id" = "accountId"})
	 */
	public function createAction(Account $account) {
		return $this->update($account, new Address());
	}

	/**
	 * @Route(
	 *      "/{accountId}/account-address-update/{id}",
	 *      name="mekit_account_address_update",
	 *      requirements={"accountId"="\d+","id"="\d+"}, defaults={"id"=0},
	 *      options={"expose"="true"}
	 * )
	 * @Template(template="MekitContactInfoBundle:AccountAddress:update.html.twig")
	 * @AclAncestor("mekit_account_account_update")
	 * @ParamConverter("account", options={"id" = "accountId"})
	 */
	public function updateAction(Account $account, Address $address) {
		return $this->update($account, $address);
	}

	/**
	 * @param Account $account
	 * @param Address $address
	 * @return array
	 * @throws BadRequestHttpException
	 */
	protected function update(Account $account, Address $address) {
		$responseData = array(
			'saved' => false,
			'account' => $account
		);

		if ($this->getRequest()->getMethod() == 'GET' && !$address->getId()) {
			if (!$account->getAddresses()->count()) {
				$address->setPrimary(true);
			}
		}

		if ($address->getOwnerAccount() && $address->getOwnerAccount()->getId() != $account->getId()) {
			throw new BadRequestHttpException('Address must belong to an account');
		} elseif (!$address->getOwnerAccount()) {
			$account->addAddress($address);
		}

		// Update accounts's modification date when an address is changed
		$account->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));


		if ($this->get('mekit_contact_info.form.handler.address')->process($address)) {
			$this->getDoctrine()->getManager()->flush();
			$responseData['entity'] = $address;
			$responseData['saved'] = true;
		}

		$responseData['form'] = $this->get('mekit_contact_info.form.address')->createView();

		return $responseData;
	}


}