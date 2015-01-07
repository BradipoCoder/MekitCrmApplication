<?php
namespace Mekit\Bundle\PlaygroundBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;

/**
 * Class DefaultController
 */
class PlaygroundController extends Controller {
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_playground_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @return array
	 */
	public function indexAction() {
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();
		/** @var Account $account */
		$account = $em->getRepository('Mekit\Bundle\AccountBundle\Entity\Account')->findOneBy(["name"=>"AAA"]);
		$accountRefEl = $account->getReferenceableElement();
		/** @var Contact $contact */
		$contact = $em->getRepository('Mekit\Bundle\ContactBundle\Entity\Contact')->findOneBy(["firstName"=>"Mee"]);//7
		$contactRefEl = $contact->getReferenceableElement();

		$dumpData = [];
		$dumpData['AccHasCnt-Reference'] = $accountRefEl->hasReference($contactRefEl) ? "Y" : "N";
		$dumpData['CntHasAcc-Reference'] = $contactRefEl->hasReference($accountRefEl) ? "Y" : "N";
		$dumpData['AccHasCnt-Referral'] = $accountRefEl->hasReferral($contactRefEl) ? "Y" : "N";
		$dumpData['CntHasAcc-Referral'] = $contactRefEl->hasReferral($accountRefEl) ? "Y" : "N";


		$accReferences = $accountRefEl->getReferences();
		$dumpData['AccountReferences'] = $accReferences->count();

		/** @var ReferenceableElement $accRef1 */
//		$accRef1 = $accReferences->first();
//		$dumpData['AccRef1-id'] = $accRef1->getId();
//		$dumpData['AccRef1-type'] = $accRef1->getType();

		$dumpData['AccRef'] = [];
		/** @var ReferenceableElement $accReference */
		foreach($accReferences as $accReference) {
			//find out referenced item
			$reffedItem = $em->getRepository($accReference->getType())->findOneBy(["referenceableElement"=>$accReference->getId()]);
			if($reffedItem) {
				$dumpData['AccRef'][] = $reffedItem->__toString();
			}
		}





		//clear all
//		$accountRefEl->setReferences([]);
//		$contactRefEl->setReferences([]);
//		$dumpData['NumRefs3'] = $accountRefEl->getReferences()->count();

		$em->persist($account);
		$em->flush();



		$data = [
			'name' => 'Test',
			'dumpdata' => $dumpData
		];
		return $this->render('MekitPlaygroundBundle:Default:index.html.twig', $data);
	}
}
