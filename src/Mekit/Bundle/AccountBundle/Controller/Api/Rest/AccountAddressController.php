<?php

namespace Mekit\Bundle\AccountBundle\Controller\Api\Rest;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\AccountBundle\Entity\AccountAddress;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("address")
 * @NamePrefix("mekit_api_")
 */
class AccountAddressController extends RestController implements ClassResourceInterface {

	/**
	 * REST GET address
	 *
	 * @param string $accountId
	 * @param string $addressId
	 *
	 * @ApiDoc(
	 *      description="Get account address",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_view")
	 * @return Response
	 */
	public function getAction($accountId, $addressId) {
		/** @var Account $account */
		$account = $this->getAccountManager()->find($accountId);

		/** @var AccountAddress $address */
		$address = $this->getManager()->find($addressId);

		$addressData = null;
		if ($address && $account->getAddresses()->contains($address)) {
			$addressData = $this->getPreparedItem($address);
		}
		$responseData = $addressData ? json_encode($addressData) : '';
		return new Response($responseData, $address ? Codes::HTTP_OK : Codes::HTTP_NOT_FOUND);
	}

	/**
	 * REST GET list
	 *
	 * @ApiDoc(
	 *      description="Get all address items",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_view")
	 * @param int $accountId
	 *
	 * @return JsonResponse
	 */
	public function cgetAction($accountId) {
		/** @var Account $account */
		$account = $this->getAccountManager()->find($accountId);
		$result = [];

		if (!empty($account)) {
			$items = $account->getAddresses();

			foreach ($items as $item) {
				$result[] = $this->getPreparedItem($item);
			}
		}

		return new JsonResponse(
			$result,
			empty($account) ? Codes::HTTP_NOT_FOUND : Codes::HTTP_OK
		);
	}

	/**
	 * REST DELETE address
	 *
	 * @ApiDoc(
	 *      description="Delete address items",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_delete")
	 * @param     $accountId
	 * @param int $addressId
	 *
	 * @return Response
	 */
	public function deleteAction($accountId, $addressId) {
		/** @var AccountAddress $address */
		$address = $this->getManager()->find($addressId);
		/** @var Account $account */
		$account = $this->getAccountManager()->find($accountId);
		if ($account->getAddresses()->contains($address)) {
			$account->removeAddress($address);
			// Update account's modification date when an address is removed
			$account->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
			return $this->handleDeleteRequest($addressId);
		} else {
			return $this->handleView($this->view(null, Codes::HTTP_NOT_FOUND));
		}
	}

	/**
	 * REST GET address by type
	 *
	 * @param string $accountId
	 * @param string $typeName
	 *
	 * @ApiDoc(
	 *      description="Get account address by type",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_view")
	 * @return Response
	 */
	public function getByTypeAction($accountId, $typeName) {
		/** @var Account $account */
		$account = $this->getAccountManager()->find($accountId);

		if ($account) {
			$address = $account->getAddressByTypeName($typeName);
		} else {
			$address = null;
		}

		$responseData = $address ? json_encode($this->getPreparedItem($address)) : '';

		return new Response($responseData, $address ? Codes::HTTP_OK : Codes::HTTP_NOT_FOUND);
	}

	/**
	 * REST GET primary address
	 *
	 * @param string $accountId
	 *
	 * @ApiDoc(
	 *      description="Get account primary address",
	 *      resource=true
	 * )
	 * @AclAncestor("mekit_account_view")
	 * @return Response
	 */
	public function getPrimaryAction($accountId) {
		/** @var Account $account */
		$account = $this->getAccountManager()->find($accountId);

		if ($account) {
			$address = $account->getPrimaryAddress();
		} else {
			$address = null;
		}

		$responseData = $address ? json_encode($this->getPreparedItem($address)) : '';

		return new Response($responseData, $address ? Codes::HTTP_OK : Codes::HTTP_NOT_FOUND);
	}

	protected function getAccountManager() {
		return $this->get('mekit_account.account.manager.api');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getManager() {
		return $this->get('mekit_account.account_address.manager.api');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getForm() {
		throw new \BadMethodCallException('Form is not available.');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormHandler() {
		throw new \BadMethodCallException('FormHandler is not available.');
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getPreparedItem($entity, $resultFields = []) {
		// convert addresses to plain array
		$addressTypesData = array();

		/** @var $entity AccountAddress */
		foreach ($entity->getTypes() as $addressType) {
			$addressTypesData[] = parent::getPreparedItem($addressType);
		}

		$result = parent::getPreparedItem($entity);
		$result['types'] = $addressTypesData;
		$result['countryIso2'] = $entity->getCountry()->getIso2Code();
		$result['countryIso3'] = $entity->getCountry()->getIso3Code();
		$result['regionCode'] = $entity->getRegionCode();

		unset($result['owner']);

		return $result;
	}
}