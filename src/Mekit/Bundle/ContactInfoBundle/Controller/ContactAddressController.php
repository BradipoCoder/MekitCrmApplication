<?php
namespace Mekit\Bundle\ContactInfoBundle\Controller;

use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ContactInfoBundle\Entity\Address;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ContactAddressController
 */
class ContactAddressController extends Controller {
	/**
	 * @Route("/contact-address-book/{id}", name="mekit_contact_address_book", requirements={"id"="\d+"})
	 * @Template(template="MekitContactInfoBundle:ContactAddress/widget:addressBook.html.twig")
	 * @AclAncestor("mekit_contact_view")
	 */
	public function addressBookAction(Contact $contact) {
		return array(
			'entity' => $contact,
			'address_edit_acl_resource' => 'mekit_contact_update'
		);
	}

	/**
	 * @Route(
	 *      "/{contactId}/contact-address-create",
	 *      name="mekit_contact_address_create",
	 *      requirements={"contactId"="\d+"}
	 * )
	 * @Template("MekitContactInfoBundle:ContactAddress:update.html.twig")
	 * @AclAncestor("mekit_contact_create")
	 * @ParamConverter("contact", options={"id" = "contactId"})
	 */
	public function createAction(Contact $contact) {
		return $this->update($contact, new Address());
	}

	/**
	 * @Route(
	 *      "/{contactId}/contact-address-update/{id}",
	 *      name="mekit_contact_address_update",
	 *      requirements={"contactId"="\d+","id"="\d+"}, defaults={"id"=0}
	 * )
	 * @Template("MekitContactInfoBundle:ContactAddress:update.html.twig")
	 * @AclAncestor("mekit_contact_update")
	 * @ParamConverter("contact", options={"id" = "contactId"})
	 */
	public function updateAction(Contact $contact, Address $address) {
		return $this->update($contact, $address);
	}

	/**
	 * @param Contact $contact
	 * @param Address $address
	 * @return array
	 * @throws BadRequestHttpException
	 */
	protected function update(Contact $contact, Address $address) {
		$responseData = array(
			'saved' => false,
			'contact' => $contact
		);

		if ($this->getRequest()->getMethod() == 'GET' && !$address->getId()) {
			$address->setFirstName($contact->getFirstName());
			$address->setLastName($contact->getLastName());
			if (!$contact->getAddresses()->count()) {
				$address->setPrimary(true);
			}
		}

		if ($address->getOwnerContact() && $address->getOwnerContact()->getId() != $contact->getId()) {
			throw new BadRequestHttpException('Address must belong to a contact');
		} elseif (!$address->getOwnerContact()) {
			$contact->addAddress($address);
		}

		// Update contacts's modification date when an address is changed
		$contact->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));

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