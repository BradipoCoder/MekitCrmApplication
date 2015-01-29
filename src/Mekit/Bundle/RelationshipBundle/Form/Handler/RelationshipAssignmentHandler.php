<?php
namespace Mekit\Bundle\RelationshipBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;

class RelationshipAssignmentHandler {
	/**
	 * @var FormInterface
	 */
	protected $form;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var ObjectManager
	 */
	protected $manager;

	/**
	 *
	 * @param FormInterface $form
	 * @param Request       $request
	 * @param ObjectManager $manager
	 */
	public function __construct(FormInterface $form, Request $request, ObjectManager $manager) {
		$this->form = $form;
		$this->request = $request;
		$this->manager = $manager;
	}

	/**
	 * Process form
	 *
	 * @param  ReferenceableElement $entity
	 * @return bool True on successful processing, false otherwise
	 */
	public function process(ReferenceableElement $entity) {
		$this->form->setData($entity);
		if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
			$this->form->submit($this->request);
			if ($this->form->isValid()) {
				$appendElements = $this->form->get('appendElements')->getData();
				$removeElements = $this->form->get('removeElements')->getData();
				$this->onSuccess($entity, $appendElements, $removeElements);
				return true;
			}
		}
		return false;
	}

	/**
	 * "Success" form handler
	 * @param ReferenceableElement $entity
	 * @param ReferenceableElement[]                $appendElements
	 * @param ReferenceableElement[]                $removeElements
	 */
	protected function onSuccess(ReferenceableElement $entity, array $appendElements, array $removeElements) {
		$this->addReferences($entity, $appendElements);
		$this->removeReferences($entity, $removeElements);
		$this->manager->persist($entity);
		$this->manager->flush();
	}

	/**
	 * Append references to ReferenceableElement
	 *
	 * @param ReferenceableElement   $entity
	 * @param ReferenceableElement[] $references
	 */
	protected function addReferences(ReferenceableElement $entity, array $references) {
		foreach ($references as $reference) {
			$entity->addReference($reference);
		}
	}

	/**
	 * Remove references/referrals from ReferenceableElement
	 *
	 * @param ReferenceableElement   $entity
	 * @param ReferenceableElement[] $references
	 */
	protected function removeReferences(ReferenceableElement $entity, array $references) {
		foreach ($references as $reference) {
			if($entity->hasReference($reference)) {
				$entity->removeReference($reference);
			}
			if($entity->hasReferral($reference)) {
				$reference->removeReference($entity);
			}
		}
	}
}